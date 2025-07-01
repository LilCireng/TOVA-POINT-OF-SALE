@extends('layouts.app')
@section('title', 'Daftar Supplier')

@section('content')
    <h1 class="h3 mb-3"><i class="fa-solid fa-truck"></i>
    Manajemen Supplier
</h1>

    <div class="mb-4">
        <a href="#" onclick="document.getElementById('modalSupplier').style.display='flex'" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Tambah Supplier Baru
        </a>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Supplier</th>
                    <th>No. Telepon</th>
                    <th>Alamat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($suppliers as $supplier)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $supplier['nama'] }}</td>
                        <td>{{ $supplier['telepon'] }}</td>
                        <td>{{ $supplier['alamat'] }}</td>
                        <td>
                            <a href="{{ route('supplier.edit', $supplier->id) }}" class="btn btn-warning"><i class="fa-solid fa-pencil"></i> Edit</a>

                            <form action="{{ route('supplier.destroy', $supplier->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus supplier ini?')">
                                    <i class="fa-solid fa-trash"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center;">Tidak ada data supplier.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div id="modalSupplier" style="display: none; position: fixed; top: 0; left: 0;
            width: 100%; height: 100%; background: rgba(0,0,0,0.5); justify-content: center; align-items: center;">
        <div style="background: white; padding: 20px; width: 400px; border-radius: 8px;">
            <h2>Tambah Supplier</h2>
            <form method="POST" action="{{ route('supplier.store') }}">
                @csrf
                <div class="form-group">
                    <label>Nama Supplier</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>No. Telepon</label>
                    <input type="text" name="telepon" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Alamat</label>
                    <textarea name="alamat" class="form-control" required></textarea>
                </div>
                <br>
                <button type="submit" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('modalSupplier').style.display='none'">Batal</button>
            </form>
        </div>
    </div>
@endsection