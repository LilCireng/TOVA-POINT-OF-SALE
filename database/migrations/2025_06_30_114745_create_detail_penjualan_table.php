<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Perintah ini akan membuat tabel 'detail_penjualan'
        Schema::create('detail_penjualan', function (Blueprint $table) {
            $table->id(); // Kolom ID untuk setiap baris detail

            // Foreign key yang terhubung ke tabel 'penjualans'
            // Jika data penjualan dihapus, detailnya juga ikut terhapus (cascade)
            $table->foreignId('penjualan_id')->constrained('penjualans')->onDelete('cascade');

            // Foreign key yang terhubung ke tabel 'barangs'
            // Jika barang dihapus, ID barang di sini akan jadi NULL (set null)
            $table->foreignId('barang_id')->nullable()->constrained('barangs')->onDelete('set null');

            $table->integer('jumlah'); // Jumlah barang yang dibeli
            $table->decimal('harga_satuan', 15, 2); // Harga barang saat transaksi
            $table->decimal('subtotal', 15, 2); // Total harga (jumlah * harga_satuan)
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_penjualan');
    }
};
 