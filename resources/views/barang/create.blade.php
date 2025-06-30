@extends('layouts.app')
@section('title', 'Tambah Barang Baru')

@section('content')
    <div class="page-header">
        <h1><i class="fa-solid fa-plus-circle"></i> Tambah Barang Baru</h1>
        <a href="{{ route('barang.index') }}" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger" style="margin-bottom: 1.5rem; background-color: #f8d7da; border-color: #f5c6cb; color: #721c24; padding: 1rem; border-radius: 6px;">
            <strong>Oops! Ada yang salah dengan input Anda.</strong>
            <ul style="margin-top: 0.5rem; margin-left: 1rem; padding-left: 1rem;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-container">
        <form action="{{ route('barang.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="nama">Nama Barang</label>
                <input type="text" id="nama" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" required>
                @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            
            <div class="form-group">
                <label>Kategori</label>
                <div class="radio-group-container @error('kategori') is-invalid @enderror">
                    @php $kategoriOptions = ['Device', 'Catridge', 'Liquid', 'Coil', 'Kapas', 'Baterai','RDA']; @endphp
                    @foreach($kategoriOptions as $kategori)
                    <div class="radio-option">
                        <input type="radio" id="kategori_{{ strtolower($kategori) }}" name="kategori" value="{{ $kategori }}" {{ old('kategori') == $kategori ? 'checked' : '' }} required>
                        <label for="kategori_{{ strtolower($kategori) }}">{{ $kategori }}</label>
                    </div>
                    @endforeach
                </div>
                @error('kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- DIUBAH: Memisahkan input harga menjadi dua --}}
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label for="harga_beli">Harga Beli</label>
                    <input type="number" id="harga_beli" name="harga_beli" class="form-control @error('harga_beli') is-invalid @enderror" value="{{ old('harga_beli') }}" required>
                    @error('harga_beli')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label for="harga_jual">Harga Jual</label>
                    <input type="number" id="harga_jual" name="harga_jual" class="form-control @error('harga_jual') is-invalid @enderror" value="{{ old('harga_jual') }}" required>
                    @error('harga_jual')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            
            <div class="form-group">
                <label for="stok">Stok Awal</label>
                <input type="number" id="stok" name="stok" class="form-control @error('stok') is-invalid @enderror" value="{{ old('stok') }}" required>
                @error('stok')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="form-group" style="text-align: right; border-top: 1px solid #f0f2f5; padding-top: 1.5rem; margin-top: 1rem; margin-bottom: 0;">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-save"></i> Simpan Barang
                </button>
            </div>
        </form>
    </div>
    {{-- CSS tidak diubah --}}
    <style>.is-invalid { border-color: #e53e3e !important; }.invalid-feedback { color: #e53e3e; font-size: 0.875rem; margin-top: 0.25rem; }.radio-group-container.is-invalid { border: 1px solid #e53e3e; border-radius: 6px; padding: 0.5rem; }</style>
@endsection