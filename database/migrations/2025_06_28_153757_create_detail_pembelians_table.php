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
        Schema::create('detail_pembelians', function (Blueprint $table) {
            $table->id();

            // Foreign key untuk menghubungkan ke tabel 'pembelians'
            $table->foreignId('pembelian_id')->constrained('pembelians')->onDelete('cascade');

            // Foreign key untuk menghubungkan ke tabel 'barangs' (ingat, model Product kita pakai tabel 'barangs')
            $table->foreignId('product_id')->constrained('barangs')->onDelete('cascade');

            $table->integer('jumlah');
            $table->decimal('harga_beli', 15, 2);
            
            // Kita tidak perlu timestamps untuk tabel detail ini
        });
    }
};
