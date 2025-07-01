@extends('layouts.app')
@section('title', 'Daftar Barang')

@section('content')
<h1 class="h3 mb-3"><i class="fa-solid fa-box"></i>
                        Daftar Barang
</h1>

<div class="mb-4">
    <a href="{{ route('barang.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Tambah Barang Baru
    </a>
</div>

<div class="form-container" style="margin-bottom: 1.5rem;">
    <form action="{{ route('barang.index') }}" method="GET">
        <div style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 1rem; align-items: end;">
            <div class="form-group" style="margin: 0;">
                <label for="search">Cari Nama Barang</label>
                <input type="text" name="search" id="search" class="form-control" placeholder="Ketik nama barang..." value="{{ request('search') }}">
            </div>
            <div class="form-group" style="margin: 0;">
                <label for="kategori_id">Filter Kategori</label>
                <select name="kategori_id" id="kategori_id" class="form-control">
                    <option value="">Semua Kategori</option>
                    @foreach ($kategoris as $kategori)
                        <option value="{{ $kategori->id }}" {{ request('kategori_id') == $kategori->id ? 'selected' : '' }}>
                            {{ $kategori->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group" style="margin: 0;">
                <button type="submit" class="btn btn-primary">Cari</button>
            </div>
        </div>
    </form>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th style="text-align: right;">Biaya</th>
                <th style="text-align: right;">Harga Jual</th>
                <th style="text-align: right;">Margin</th>
                <th style="text-align: center;">Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($barangs as $barang)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $barang->nama }}</td>
                    <td>{{ optional($barang->kategori)->nama_kategori }}</td>
                    <td style="text-align: right;">Rp {{ number_format($barang->harga_beli, 0, ',', '.') }}</td>
                    <td style="text-align: right;">Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}</td>
                    <td style="text-align: right; color: {{ $barang->margin > 0 ? 'green' : 'red' }}; font-weight: 500;">
                        Rp {{ number_format($barang->margin, 0, ',', '.') }}
                    </td>
                    <td style="text-align: center;">{{ $barang->stok }}</td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('barang.edit', $barang->id) }}" class="btn btn-warning" style="padding: 0.4rem 0.8rem;">
                                <i class="fa-solid fa-pencil"></i>
                            </a>
                            <form action="{{ route('barang.destroy', $barang->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus barang ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 0.4rem 0.8rem;">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 2rem;">Tidak ada data barang.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection