<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\Supplier;
use App\Models\Product; 
use App\Models\DetailPembelian; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembelianController extends Controller
{
    public function index()
    {
        $pembelians = Pembelian::with('supplier')->latest()->get();
        return view('pembelian.index', compact('pembelians'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $products = Product::orderBy('nama')->get(); 

        return view('pembelian.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_faktur'   => 'required|string|max:255|unique:pembelians',
            'tanggal'     => 'required|date',
            'supplier_id' => 'required|exists:suppliers,id',
            'total'       => 'required|numeric',
            'items'       => 'required|json' 
        ]);

        try {
            DB::beginTransaction();

            $pembelian = Pembelian::create([
                'no_faktur'   => $request->no_faktur,
                'tanggal'     => $request->tanggal,
                'supplier_id' => $request->supplier_id,
                'total'       => $request->total,
            ]);

            $items = json_decode($request->items, true);

            foreach ($items as $item) {
                DetailPembelian::create([
                    'pembelian_id' => $pembelian->id,
                    'product_id'   => $item['id'],
                    'jumlah'       => $item['jumlah'],
                    'harga_beli'   => $item['harga_beli'],
                ]);

                $product = Product::find($item['id']);
                if ($product) {
                    $product->stok += $item['jumlah'];
                    $product->save();
                }
            }

            DB::commit();

            return redirect()->route('pembelian.index')->with('success', 'Transaksi pembelian berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage())->withInput();
        }
    }
}