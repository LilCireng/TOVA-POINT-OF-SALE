@extends('layouts.app')
@section('title', 'Edit Supplier')

@section('styles')
<style>
    .card-form {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        padding: 30px;
        max-width: 600px;
        margin: 20px auto;
    }
    .form-group { margin-bottom: 1.5rem; }
    .btn-container { display: flex; gap: 10px; margin-top: 20px; }
</style>
@endsection

@section('content')
    <h1 class="h3 mb-4"><i class="fa-solid fa-pencil"></i> Edit Data Supplier</h1>

    <div class="card-form">
        <form action="{{ route('supplier.update', $supplier->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="nama">Nama Supplier</label>
                <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama', $supplier->nama) }}" required>
            </div>

            <div class="form-group">
                <label for="telepon">No. Telepon</label>
                <input type="text" class="form-control" id="telepon" name="telepon" value="{{ old('telepon', $supplier->telepon) }}" required>
            </div>

            <div class="form-group">
                <label for="alamat">Alamat</label>
                <textarea class="form-control" id="alamat" name="alamat" rows="4" required>{{ old('alamat', $supplier->alamat) }}</textarea>
            </div>

            <div class="btn-container">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-