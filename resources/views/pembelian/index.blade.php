@extends('layouts.app')
@section('title', 'Riwayat Pembelian')

@section('content')

<div class="page-header">
    <h1 class="page-title"><i class="fa-solid fa-clock-rotate-left"></i> 
        Data Pembelian
    </h1>
    <a href="{{ route('pembelian.create') }}" class="btn btn-primary">
        <svg class="w-5 h-5 mr-2 -ml-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
        </svg>
        Buat Transaksi Baru
    </a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table-custom">
            <thead>
                <tr>
                    <th>No Faktur</th>
                    <th>Tanggal</th>
                    <th>Supplier</th>
                    <th>Grand Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pembelians as $pembelian)
                    <tr>
                        <td>
                            <a href="#" class="link-primary">{{ $pembelian->no_faktur }}</a>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($pembelian->tanggal)->isoFormat('D MMMM YYYY, HH:mm') }}</td>
                        <td>{{ $pembelian->supplier->nama }}</td>
                        <td>Rp{{ number_format($pembelian->total, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">
                            <div class="empty-state">
                                <p>Belum ada riwayat pembelian.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    .page-title {
        font-size: 1.875rem;
        font-weight: 700;
        color: #1f2937;
    }
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.625rem 1.25rem;
        border: 1px solid transparent;
        font-size: 0.875rem;
        font-weight: 500;
        border-radius: 0.5rem;
        text-decoration: none;
        cursor: pointer;
        transition: background-color 0.2s;
    }
    .btn-primary {
        color: #ffffff;
        background-color: #4f46e5;
        box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    }
    .btn-primary:hover {
        background-color: #4338ca;
    }
    .btn-action {
        display: inline-flex;
        align-items: center;
        color: #4f46e5;
        font-weight: 500;
        text-decoration: none;
    }
    .btn-action:hover {
        color: #3730a3;
    }
    .btn svg {
        width: 1.25rem;
        height: 1.25rem;
        margin-right: 0.5rem;
    }
    .card {
        background-color: #ffffff;
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
        overflow: hidden;
    }
    .table-responsive {
        overflow-x: auto;
    }
    .table-custom {
        width: 100%;
        border-collapse: collapse;
    }
    .table-custom th, .table-custom td {
        padding: 0.75rem 1.5rem;
        white-space: nowrap;
        font-size: 0.875rem;
    }
    .table-custom thead {
        background-color: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
    }
    .table-custom th {
        text-align: left;
        font-weight: 500;
        color: #6b7280;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .table-custom tbody tr {
        border-bottom: 1px solid #e5e7eb;
    }
    .table-custom tbody tr:last-child {
        border-bottom: none;
    }
    .table-custom tbody tr:hover {
        background-color: #f9fafb;
    }
    .table-custom td {
        color: #374151;
    }
    .link-primary {
        color: #4f46e5;
        font-weight: 500;
        text-decoration: none;
    }
    .link-primary:hover {
        text-decoration: underline;
    }
    .text-center {
        text-align: center;
    }
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: #6b7280;
    }
</style>

@endsection