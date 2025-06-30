@extends('layouts.app')
@section('title', 'Transaksi Penjualan (POS)')

@push('styles')
{{-- Style untuk halaman POS --}}
<style>
    .pos-container { display: grid; grid-template-columns: 1fr; gap: 1.5rem; }
    @media (min-width: 1024px) { .pos-container { grid-template-columns: 2fr 1fr; } }
    
    .search-section { position: relative; }
    .search-results-container {
        position: absolute;
        width: 100%;
        max-height: 300px;
        overflow-y: auto;
        border: 1px solid #e2e8f0;
        border-radius: 0 0 0.5rem 0.5rem;
        background: white;
        z-index: 100;
        display: none; /* Sembunyi secara default */
    }
    .search-result-item {
        padding: 0.75rem 1rem;
        cursor: pointer;
        border-bottom: 1px solid #f1f5f9;
    }
    .search-result-item:hover { background-color: #f8fafc; }
    .quantity-input { width: 70px; text-align: center; }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1><i class="fa-solid fa-cash-register"></i> Point of Sale</h1>
</div>

{{-- Tampilkan notifikasi jika ada --}}
@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="pos-container">
    {{-- Kolom Kiri --}}
    <div class="form-container">
        <div class="form-group search-section">
            <label for="product-search">Cari Barang</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-search"></i></span>
                <input type="text" id="product-search" class="form-control" placeholder="Ketik nama atau kode barang...">
            </div>
            <div id="product-list" class="product-search-results mt-1"></div>
        </div>
        <hr class="my-4">
        <h3><i class="fa-solid fa-shopping-cart"></i> Keranjang</h3>
        <div class="table-responsive">
            <table class="table" id="cart-table">
                <thead>
                    <tr>
                        <th>Nama Barang</th>
                        <th class="text-center" style="width: 120px;">Jumlah</th>
                        <th>Harga</th>
                        <th>Subtotal</th>
                        <th style="width: 50px;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="cart-body">
                    <tr id="cart-empty-row">
                        <td colspan="5" class="text-center text-muted">Keranjang masih kosong.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Kolom Kanan --}}
    <div class="form-container">
        <h3><i class="fa-solid fa-receipt"></i> Detail Pembayaran</h3>
        <form id="form-penjualan" action="{{ route('penjualan.store') }}" method="POST">
            @csrf
            <div id="cart-hidden-inputs"></div>
            <div class="form-group">
                <label for="customer-name">Nama Pelanggan (Opsional)</label>
                <input type="text" id="customer-name" name="pelanggan" class="form-control" placeholder="Umum">
            </div>
            <div class="p-3 bg-light rounded text-end my-3">
                <div class="text-muted">Grand Total</div>
                <div id="grand-total" class="display-6 fw-bold">Rp 0</div>
            </div>
            <button type="submit" id="process-payment-btn" class="btn btn-primary w-100 btn-lg" disabled>
                <i class="fa-solid fa-check"></i> Proses Pembayaran
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
{{-- Memuat jQuery untuk AJAX --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
// ✨ LOGIKA JAVASCRIPT DIPERBAIKI TOTAL DENGAN JQUERY ✨
$(document).ready(function() {
    let cart = {}; // { id: { data... , jumlah: X } }
    let searchTimeout;
    let latestSearchResults = []; // Menyimpan hasil pencarian terakhir

    // --- FUNGSI UTAMA ---
    const renderCart = () => {
        const cartBody = $('#cart-body');
        const paymentBtn = $('#process-payment-btn');
        cartBody.empty();
        let grandTotal = 0;
        const items = Object.values(cart);

        if (items.length === 0) {
            cartBody.html('<tr id="cart-empty-row"><td colspan="5" class="text-center text-muted">Keranjang masih kosong.</td></tr>');
            paymentBtn.prop('disabled', true);
        } else {
            paymentBtn.prop('disabled', false);
            items.forEach(item => {
                const subtotal = item.harga_jual * item.jumlah;
                grandTotal += subtotal;
                const row = `
                    <tr data-id="${item.id}">
                        <td>${item.nama}</td>
                        <td class="text-center">
                            <input type="number" class="form-control quantity-input" value="${item.jumlah}" min="1" max="${item.stok}">
                        </td>
                        <td>Rp ${item.harga_jual.toLocaleString('id-ID')}</td>
                        <td class="subtotal">Rp ${subtotal.toLocaleString('id-ID')}</td>
                        <td><button type="button" class="btn btn-sm btn-danger btn-remove"><i class="fa-solid fa-trash"></i></button></td>
                    </tr>
                `;
                cartBody.append(row);
            });
        }
        $('#grand-total').text(`Rp ${grandTotal.toLocaleString('id-ID')}`);
    };

    // --- EVENT LISTENERS ---
    $('#product-search').on('keyup', function() {
        clearTimeout(searchTimeout);
        const query = $(this).val();
        const resultsContainer = $('#product-list');

        if (query.length < 2) {
            resultsContainer.hide().empty();
            return;
        }

        searchTimeout = setTimeout(() => {
            $.ajax({
                url: "{{ route('penjualan.searchBarang') }}",
                data: { query: query },
                success: function(data) {
                    resultsContainer.empty();
                    latestSearchResults = data; // Simpan hasil ke variabel
                    if (data.length) {
                        data.forEach(item => {
                            // ✨ Hanya menyimpan ID pada elemen HTML ✨
                            const itemDiv = $(`<div class="search-result-item" data-id="${item.id}"></div>`);
                            itemDiv.html(`<span>${item.nama}</span> <small class="text-muted">(Stok: ${item.stok})</small>`);
                            resultsContainer.append(itemDiv);
                        });
                        resultsContainer.show();
                    } else {
                        resultsContainer.html('<div class="p-3 text-muted">Produk tidak ditemukan.</div>').show();
                    }
                }
            });
        }, 300);
    });

    // ✨ EVENT KLIK YANG DIPERBAIKI MENGGUNAKAN JQUERY EVENT DELEGATION ✨
    $(document).on('click', '.search-result-item', function() {
        const productId = $(this).data('id');
        // Cari produk lengkap dari hasil pencarian yang disimpan
        const product = latestSearchResults.find(p => p.id === productId);

        if (!product) {
            alert('Gagal mengambil data produk. Silakan coba lagi.');
            return;
        }

        if (cart[product.id]) {
            if (cart[product.id].jumlah < product.stok) {
                cart[product.id].jumlah++;
            } else {
                alert('Stok tidak mencukupi!');
            }
        } else {
            cart[product.id] = { ...product, jumlah: 1 };
        }
        $('#product-search').val('');
        $('#product-list').hide().empty();
        renderCart();
    });

    $('#cart-body').on('change', '.quantity-input', function() {
        const row = $(this).closest('tr');
        const id = row.data('id');
        let newQty = parseInt($(this).val());

        if (newQty > cart[id].stok) {
            alert('Stok tidak mencukupi!');
            newQty = cart[id].stok;
            $(this).val(newQty);
        }
        cart[id].jumlah = newQty > 0 ? newQty : 1;
        renderCart();
    });

    $('#cart-body').on('click', '.btn-remove', function() {
        const id = $(this).closest('tr').data('id');
        delete cart[id];
        renderCart();
    });

    $('#form-penjualan').on('submit', function(e) {
        if (Object.keys(cart).length === 0) {
            e.preventDefault();
            alert('Keranjang tidak boleh kosong!');
            return;
        }
        const hiddenInputsContainer = $('#cart-hidden-inputs');
        hiddenInputsContainer.empty();
        Object.values(cart).forEach(item => {
            hiddenInputsContainer.append(`
                <input type="hidden" name="items[${item.id}][id]" value="${item.id}">
                <input type="hidden" name="items[${item.id}][jumlah]" value="${item.jumlah}">
            `);
        });
    });

    $(document).on('click', function(event) {
        if (!$(event.target).closest('.search-section').length) {
            $('#product-list').hide();
        }
    });

    renderCart();
});
</script>
@endpush
