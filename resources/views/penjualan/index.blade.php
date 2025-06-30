@extends('layouts.app')
@section('title', 'Riwayat Penjualan')

@section('content')
<div class="page-header">
    <h1><i class="fa-solid fa-receipt"></i> Riwayat Penjualan</h1>
    <a href="{{ route('penjualan.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Buat Transaksi Baru
    </a>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Invoice</th>
                <th>Tanggal</th>
                <th>Pelanggan</th>
                <th>Total Barang</th>
                <th>Grand Total</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($penjualans as $penjualan)
                <tr>
                    <td>
                        <a href="{{ route('penjualan.show', $penjualan) }}" class="font-weight-bold">
                            {{ $penjualan->nomor_invoice }}
                        </a>
                    </td>
                    <td>{{ $penjualan->created_at->format('d M Y, H:i') }}</td>
                    <td>{{ $penjualan->pelanggan }}</td>
                    {{-- DIUBAH: Menggunakan hasil dari withSum() untuk efisiensi --}}
                    <td class="text-center">{{ $penjualan->details_sum_jumlah ?? 0 }}</td>
                    <td class="text-end">Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</td>
                    <td>
                        <a href="{{ route('penjualan.show', $penjualan) }}" class="btn btn-info btn-sm">
                            <i class="fa-solid fa-eye"></i> Detail
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-4">
                        Belum ada riwayat penjualan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination Links --}}
<div class="mt-4">
    {{ $penjualans->links() }}
</div>
@endsection
