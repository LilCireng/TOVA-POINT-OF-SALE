<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\Barang;
use App\Models\PenjualanDetail;
use Illuminate\Support\Facades\DB;


class PenjualanController extends Controller
{
    public function index()
    {
        $penjualans = Penjualan::with('user')
                                ->withSum('details', 'jumlah')
                                ->latest()
                                ->paginate(10);

        return view('penjualan.index', compact('penjualans'));
    }

    public function show(Penjualan $penjualan)
    {
        $penjualan->load(['details.barang', 'user']);

        return view('penjualan.show', compact('penjualan'));
    }

    public function create()
    {
        return view('penjualan.pos');
    }

    public function searchBarang(Request $request)
    {
        $query = $request->get('query');
        if ($query) {
            $barang = Barang::where('nama', 'LIKE', "%{$query}%")
                            ->where('stok', '>', 0)
                            ->limit(10)
                            ->get();
            return response()->json($barang);
        }
        return response()->json([]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:barangs,id',
            'items.*.jumlah' => 'required|integer|min:1',
            'pelanggan' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $today = now()->format('Ymd');
            $penjualanHariIni = Penjualan::whereDate('created_at', today())->count();
            $nomorUrut = str_pad($penjualanHariIni + 1, 4, '0', STR_PAD_LEFT);
            $nomorInvoice = "INV/{$today}/{$nomorUrut}";

            $penjualan = Penjualan::create([
                'nomor_invoice' => $nomorInvoice,
                'total_harga' => 0,
                'user_id' => auth()->id(),
                'pelanggan' => $request->pelanggan ?? 'Umum',
            ]);

            $grandTotal = 0;

            foreach ($request->items as $itemData) {
                $barang = Barang::find($itemData['id']);

                if (!$barang || $barang->stok < $itemData['jumlah']) {
                    throw new \Exception('Stok untuk barang ' . ($barang->nama ?? 'yang dipilih') . ' tidak mencukupi.');
                }

                $subtotal = $barang->harga_jual * $itemData['jumlah'];
                $grandTotal += $subtotal;

                PenjualanDetail::create([
                    'penjualan_id' => $penjualan->id,
                    'barang_id' => $barang->id,
                    'jumlah' => $itemData['jumlah'],
                    'harga_satuan' => $barang->harga_jual,
                    'subtotal' => $subtotal,
                ]);

                $barang->decrement('stok', $itemData['jumlah']);
            }

            $penjualan->total_harga = $grandTotal;
            $penjualan->save();

            DB::commit();

            return redirect()->route('penjualan.create')->with('success', 'Transaksi berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }
}