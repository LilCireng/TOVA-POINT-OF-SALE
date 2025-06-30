<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\Supplier;
use App\Models\Product; // <-- DITAMBAHKAN: Untuk mengambil data produk
use App\Models\DetailPembelian; // <-- DITAMBAHKAN: Untuk menyimpan detail
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembelianController extends Controller
{
    /**
     * Menampilkan daftar semua data pembelian.
     */
    public function index()
    {
        $pembelians = Pembelian::with('supplier')->latest()->get();
        return view('pembelian.index', compact('pembelians'));
    }

    /**
     * Menampilkan form untuk membuat data pembelian baru.
     */
    public function create()
    {
        // DIUBAH: Kita tambahkan pengambilan data produk di sini
        $suppliers = Supplier::all();
        $products = Product::orderBy('nama')->get(); // Ambil semua produk untuk dropdown

        return view('pembelian.create', compact('suppliers', 'products'));
    }

    /**
     * Menyimpan data pembelian baru ke database.
     * INI ADALAH SATU-SATUNYA FUNGSI store() YANG SEHARUSNYA ADA
     */
    public function store(Request $request)
    {
        // 1. Validasi data utama dari form
        $request->validate([
            'no_faktur'   => 'required|string|max:255|unique:pembelians',
            'tanggal'     => 'required|date',
            'supplier_id' => 'required|exists:suppliers,id',
            'total'       => 'required|numeric',
            'items'       => 'required|json' // Validasi bahwa 'items' ada dan berupa JSON
        ]);

        try {
            // 2. Mulai transaksi database untuk menjaga konsistensi data
            DB::beginTransaction();

            // 3. Simpan data header pembelian
            $pembelian = Pembelian::create([
                'no_faktur'   => $request->no_faktur,
                'tanggal'     => $request->tanggal,
                'supplier_id' => $request->supplier_id,
                'total'       => $request->total,
            ]);

            // 4. Simpan data detail pembelian (item-item produk)
            $items = json_decode($request->items, true);

            foreach ($items as $item) {
                // Pastikan model DetailPembelian sudah Anda buat
                DetailPembelian::create([
                    'pembelian_id' => $pembelian->id,
                    'product_id'   => $item['id'],
                    'jumlah'       => $item['jumlah'],
                    'harga_beli'   => $item['harga_beli'],
                ]);

                // 5. (Opsional) Update stok produk
                $product = Product::find($item['id']);
                if ($product) {
                    $product->stok += $item['jumlah'];
                    $product->save();
                }
            }

            // 6. Jika semua proses berhasil, simpan semua perubahan ke database
            DB::commit();

            return redirect()->route('pembelian.index')->with('success', 'Transaksi pembelian berhasil disimpan.');

        } catch (\Exception $e) {
            // 7. Jika terjadi error di tengah jalan, batalkan semua perubahan
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage())->withInput();
        }
    }

    // FUNGSI store() YANG LAMA SUDAH DIHAPUS DARI SINI
}