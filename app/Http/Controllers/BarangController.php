<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Kategori;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        $query = Barang::with('kategori');

        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('kategori_id')) {
            $query->where('id_kategori', $request->kategori_id);
        }

        $barangs = $query->latest()->get();

        return view('barang.index', compact('barangs', 'kategoris'));
    }

    public function create()
    {
        return view('barang.create');
    }

    public function store(Request $request)
    {
        // DIUBAH: Validasi untuk harga_beli dan harga_jual
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255|unique:barangs,nama',
            'kategori' => 'required|string',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0|gte:harga_beli', // Harga jual tidak boleh lebih rendah dari harga beli
            'stok' => 'required|integer|min:0',
        ]);

        $kategori = Kategori::firstOrCreate(['nama_kategori' => $validatedData['kategori']]);

        // DIUBAH: Menyimpan harga_beli dan harga_jual
        Barang::create([
            'nama' => $validatedData['nama'],
            'id_kategori' => $kategori->id,
            'stok' => $validatedData['stok'],
            'harga_beli' => $validatedData['harga_beli'],
            'harga_jual' => $validatedData['harga_jual'],
        ]);

        return redirect()->route('barang.index')->with('success', 'Barang baru berhasil ditambahkan!');
    }

    public function edit(Barang $barang)
    {
        // Controller 'edit' tidak perlu diubah karena view-nya sudah kita perbaiki
        return view('barang.edit', compact('barang'));
    }

    public function update(Request $request, Barang $barang)
    {
        // DIUBAH: Validasi untuk harga_beli dan harga_jual
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255|unique:barangs,nama,' . $barang->id,
            'kategori' => 'required|string',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0|gte:harga_beli',
            'stok' => 'required|integer|min:0',
        ]);

        $kategori = Kategori::firstOrCreate(['nama_kategori' => $validatedData['kategori']]);

        // DIUBAH: Mengupdate harga_beli dan harga_jual
        $barang->update([
            'nama' => $validatedData['nama'],
            'id_kategori' => $kategori->id,
            'stok' => $validatedData['stok'],
            'harga_beli' => $validatedData['harga_beli'],
            'harga_jual' => $validatedData['harga_jual'],
        ]);

        return redirect()->route('barang.index')->with('success', 'Data barang berhasil diperbarui!');
    }

    public function destroy(Barang $barang)
    {
        try {
            $barang->delete();
            return redirect()->route('barang.index')->with('success', 'Data barang berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('barang.index')->with('error', 'Gagal menghapus barang. Mungkin ada data terkait.');
        }
    }
}