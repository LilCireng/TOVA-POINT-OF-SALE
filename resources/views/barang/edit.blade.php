@extends('layouts.app')
@section('title', 'Edit Barang')

@section('content')
<div class="page-header">
    <h1><i class="fa-solid fa-pencil"></i> Edit Barang</h1>
    <a href="{{ route('barang.index') }}" class="btn btn-secondary">
        <i class="fa-solid fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="form-container">
    <form action="{{ route('barang.update', $barang->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="nama">Nama Barang</label>
            <input type="text" id="nama" name="nama" class="form-control" value="{{ old('nama', $barang->nama) }}" required>
        </div>
        
        {{-- DIUBAH: Menggunakan radio button agar konsisten dengan form 'create' --}}
        <div class="form-group">
            <label>Kategori</label>
            <div class="radio-group-container">
                @php $kategoriOptions = ['Device', 'Catridge', 'Liquid', 'Coil', 'Kapas', 'Baterai']; @endphp
                @foreach($kategoriOptions as $kategoriOption)
                <div class="radio-option">
                    {{-- Cek apakah kategori barang saat ini sama dengan opsi --}}
                    <input type="radio" id="kategori_{{ strtolower($kategoriOption) }}" name="kategori" value="{{ $kategoriOption }}" {{ old('kategori', $barang->kategori->nama_kategori) == $kategoriOption ? 'checked' : '' }} required>
                    <label for="kategori_{{ strtolower($kategoriOption) }}">{{ $kategoriOption }}</label>
                </div>
                @endforeach
            </div>
        </div>

        {{-- DIUBAH: Memisahkan input harga menjadi dua --}}
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label for="harga_beli">Harga Beli (Biaya)</label>
                <input type="number" id="harga_beli" name="harga_beli" class="form-control" value="{{ old('harga_beli', $barang->harga_beli) }}" required>
            </div>
            <div class="form-group">
                <label for="harga_jual">Harga Jual</label>
                <input type="number" id="harga_jual" name="harga_jual" class="form-control" value="{{ old('harga_jual', $barang->harga_jual) }}" required>
            </div>
        </div>
        
        <div class="form-group">
            <label for="stok">Stok</label>
            <input type="number" id="stok" name="stok" class="form-control" value="{{ old('stok', $barang->stok) }}" required>
        </div>

        <div class="form-group" style="text-align: right; border-top: 1px solid #f0f2f5; padding-top: 1.5rem; margin-top: 1rem; margin-bottom: 0;">
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-save"></i> Update Barang
            </button>
        </div>
    </form>
</div>
@endsection