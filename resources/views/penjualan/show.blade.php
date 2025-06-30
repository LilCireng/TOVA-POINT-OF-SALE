@extends('layouts.app')
@section('title', 'Detail Penjualan ' . $penjualan->nomor_invoice)

@section('content')
<div class="page-header">
    <h1><i class="fa-solid fa-receipt"></i> Detail Penjualan</h1>
    <a href="{{ route('penjualan.index') }}" class="btn btn-secondary">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Riwayat
    </a>
</div>

<div class="form-container">
    {{-- Info Invoice --}}
    <div class="row border-bottom pb-3 mb-3">
        <div class="col-md-6">
            <p class="mb-1"><strong>No. Invoice:</strong></p>
            <h4 class="mb-0">{{ $penjualan->nomor_invoice }}</h4>
        </div>
        <div class="col-md-6 text-md-end">
            <p class="mb-1"><strong>Tanggal Transaksi:</strong></p>
            <h4 class="mb-0">{{ $penjualan->created_at->format('d F Y, H:i') }}</h4>
        </div>
         <div class="col-md-6 mt-3">
            <p class="mb-1"><strong>Nama Pelanggan:</strong></p>
            <h4 class="mb-0">{{ $penjualan->pelanggan }}</h4>
        </div>
    </div>

    {{-- Daftar Barang --}}
    <h3 class="mb-3">Daftar Barang</h3>
    <div class="table-responsive">
        <table class="table">
            <thead class="table-light">
                <tr>
                    <th>No.</th>
                    <th>Nama Barang</th>
                    <th class="text-end">Harga Satuan</th>
                    <th class="text-center">Jumlah</th>
                    <th class="text-end">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($penjualan->details as $detail)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        {{-- DIUBAH: Menggunakan 'nama' bukan 'nama_barang' --}}
                        <td>{{ $detail->barang->nama ?? 'Barang Telah Dihapus' }}</td>
                        {{-- DIUBAH: Menggunakan 'harga_satuan' bukan 'harga' --}}
                        <td class="text-end">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                        <td class="text-center">{{ $detail->jumlah }}</td>
                        <td class="text-end">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="fw-bold fs-5">
                    <td colspan="4" class="text-end">Grand Total</td>
                    <td class="text-end">Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    {{-- Tombol Aksi --}}
    <div class="text-end mt-4">
        <button class="btn btn-primary" onclick="window.print();">
            <i class="fa-solid fa-print"></i> Cetak Struk
        </button>
    </div>
</div>
@endsection
