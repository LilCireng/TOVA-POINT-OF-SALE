<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();

            // DIUBAH: dari 'nama_barang' menjadi 'nama' agar konsisten
            $table->string('nama_barang');

            // Ini sudah bagus, kita pertahankan
            $table->foreignId('id_kategori')->constrained('kategoris');

            // DIUBAH: Kita ganti 'harga' dengan 'harga_beli' dan 'harga_jual'
            // Kita juga gunakan tipe data decimal untuk uang
            $table->decimal('harga_beli', 15, 2)->default(0);
            $table->decimal('harga_jual', 15, 2)->default(0);

            // Kita tambahkan default(0) agar lebih aman
            $table->integer('stok')->default(0);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};