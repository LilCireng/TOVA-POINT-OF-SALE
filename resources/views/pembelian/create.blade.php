@extends('layouts.app')
@section('title', 'Input Pembelian')

@section('content')

<div class="page-header">
    <h1>
        <i class="fa-solid fa-dolly"></i> 
        Input Pembelian
    </h1>
    <a href="{{ route('pembelian.index') }}" class="btn btn-secondary">
        <i class="fa-solid fa-clock-rotate-left"></i> 
        Data Pembelian
    </a>
</div>

{{-- Form utama kita bagi menjadi dua kolom seperti di POS --}}
<div class="pembelian-container">

    <div class="form-container" style="margin-bottom: 1.5rem;">
        <form id="pembelian-form" action="{{ route('pembelian.store') }}" method="POST">
            @csrf
            {{-- Bagian ini akan menyimpan data detail pembelian (diisi oleh JavaScript) --}}
            <input type="hidden" name="items" id="items-input">
            <input type="hidden" name="total" id="total-input">

            <div class="form-group">
                <label for="no_faktur">No. Faktur</label>
                <input type="text" name="no_faktur" id="no_faktur" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="tanggal">Tanggal Pembelian</label>
                <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
            </div>

            <div class="form-group">
                <label for="supplier_id">Supplier</label>
                <select name="supplier_id" id="supplier_id" class="form-control" required>
                    <option value="">-- Pilih Supplier --</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->nama }}</option>
                    @endforeach
                </select>
            </div>
        </form>

        <hr style="margin: 2rem 0;">

        {{-- Form Pencarian Produk --}}
        <div class="form-group">
            <label for="search-produk">Cari Produk</label>
            <div style="display: flex; gap: 0.5rem;">
                {{-- Asumsi Anda punya data produk yang dikirim dari controller --}}
                <select id="search-produk" class="form-control">
                    <option value="">-- Pilih Produk --</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" data-nama="{{ $product->nama }}" data-harga_beli="{{ $product->harga_beli }}">{{ $product->nama }}</option>
                    @endforeach
                </select>
                <button id="add-item-btn" class="btn btn-primary" style="white-space: nowrap;">
                    <i class="fa-solid fa-plus"></i> Tambah
                </button>
            </div>
        </div>
    </div>

    <div class="form-container">
        <h3>Detail Barang</h3>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Nama Barang</th>
                        <th style="width: 120px;">Jumlah</th>
                        <th style="width: 150px;">Harga Beli</th>
                        <th style="width: 150px;">Subtotal</th>
                        <th style="width: 60px;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="pembelian-items">
                    {{-- Item akan ditambahkan di sini oleh JavaScript --}}
                    <tr>
                        <td colspan="5" style="text-align: center; color: #888;">Belum ada barang yang ditambahkan.</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="summary-container">
            <h4>Grand Total</h4>
            <h2 id="grand-total">Rp 0</h2>
        </div>
        <div class="form-group" style="text-align: right; margin-top: 1.5rem;">
            <button type="submit" form="pembelian-form" class="btn btn-primary">
                <i class="fa-solid fa-save"></i> Simpan Transaksi Pembelian
            </button>
        </div>
    </div>

</div>

{{-- Tambahkan sedikit CSS untuk layout 2 kolom --}}
<style>
    .pembelian-container {
        display: grid;
        grid-template-columns: 1fr; /* Default 1 kolom untuk mobile */
        gap: 1.5rem;
    }

    /* Layout 2 kolom untuk layar lebih besar */
    @media (min-width: 992px) {
        .pembelian-container {
            grid-template-columns: 1fr 2fr;
        }
    }

    .summary-container {
        text-align: right;
        margin-top: 1.5rem;
        padding-top: 1rem;
        border-top: 2px solid #f0f2f5;
    }

    .summary-container h4 {
        color: #6c757d;
        font-weight: 600;
        margin: 0;
    }

    .summary-container h2 {
        color: #1a202c;
        font-weight: 700;
        margin: 0;
    }
</style>


{{-- JavaScript untuk Interaktivitas --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const addItemBtn = document.getElementById('add-item-btn');
        const searchProdukSelect = document.getElementById('search-produk');
        const pembelianItemsTbody = document.getElementById('pembelian-items');
        const grandTotalEl = document.getElementById('grand-total');
        const itemsInput = document.getElementById('items-input');
        const totalInput = document.getElementById('total-input');

        let items = []; // Array untuk menyimpan data item
        let itemCounter = 0;

        addItemBtn.addEventListener('click', function () {
            const selectedOption = searchProdukSelect.options[searchProdukSelect.selectedIndex];
            if (!selectedOption.value) {
                alert('Silakan pilih produk terlebih dahulu.');
                return;
            }

            const productId = selectedOption.value;
            
            // Cek jika produk sudah ada di daftar
            if (items.find(item => item.id == productId)) {
                alert('Produk ini sudah ada di dalam daftar.');
                return;
            }

            const productName = selectedOption.dataset.nama;
            const productPrice = parseFloat(selectedOption.dataset.harga_beli) || 0;
            
            // Tambahkan ke array
            itemCounter++;
            const newItem = {
                id: productId,
                nama: productName,
                harga_beli: productPrice,
                jumlah: 1,
                subtotal: productPrice,
                row_id: `item-${itemCounter}`
            };
            items.push(newItem);
            
            renderItems();
            updateTotals();
        });

        function renderItems() {
            // Kosongkan tabel
            if (items.length === 0) {
                 pembelianItemsTbody.innerHTML = '<tr><td colspan="5" style="text-align: center; color: #888;">Belum ada barang yang ditambahkan.</td></tr>';
            } else {
                pembelianItemsTbody.innerHTML = '';
                items.forEach(item => {
                    const row = document.createElement('tr');
                    row.id = item.row_id;
                    row.innerHTML = `
                        <td>${item.nama}</td>
                        <td><input type="number" value="${item.jumlah}" min="1" class="form-control item-jumlah" data-id="${item.id}"></td>
                        <td><input type="number" value="${item.harga_beli}" min="0" class="form-control item-harga" data-id="${item.id}"></td>
                        <td class="item-subtotal">Rp ${item.subtotal.toLocaleString('id-ID')}</td>
                        <td><button type="button" class="btn btn-danger btn-sm remove-item" data-id="${item.id}"><i class="fa-solid fa-trash"></i></button></td>
                    `;
                    pembelianItemsTbody.appendChild(row);
                });
            }
        }
        
        function updateTotals() {
            let grandTotal = 0;
            items.forEach(item => {
                item.subtotal = item.jumlah * item.harga_beli;
                grandTotal += item.subtotal;

                // Update tampilan subtotal di baris tabel
                const row = document.getElementById(item.row_id);
                if (row) {
                    row.querySelector('.item-subtotal').textContent = `Rp ${item.subtotal.toLocaleString('id-ID')}`;
                }
            });

            grandTotalEl.textContent = `Rp ${grandTotal.toLocaleString('id-ID')}`;
            
            // Update input hidden untuk form submission
            itemsInput.value = JSON.stringify(items.map(i => ({id: i.id, jumlah: i.jumlah, harga_beli: i.harga_beli})));
            totalInput.value = grandTotal;
        }

        // Event listener untuk input jumlah, harga, dan tombol hapus
        pembelianItemsTbody.addEventListener('input', function(e) {
            const target = e.target;
            const productId = target.dataset.id;
            const item = items.find(i => i.id == productId);

            if (!item) return;

            if (target.classList.contains('item-jumlah')) {
                item.jumlah = parseInt(target.value) || 1;
            }

            if (target.classList.contains('item-harga')) {
                item.harga_beli = parseFloat(target.value) || 0;
            }
            updateTotals();
        });

        pembelianItemsTbody.addEventListener('click', function(e) {
            if (e.target.closest('.remove-item')) {
                const button = e.target.closest('.remove-item');
                const productId = button.dataset.id;
                
                // Hapus item dari array
                items = items.filter(item => item.id != productId);
                
                renderItems();
                updateTotals();
            }
        });

    });
</script>

@endsection