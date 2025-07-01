<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_pembelians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembelian_id')->constrained('pembelians')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('barangs')->onDelete('cascade');
            $table->integer('jumlah');
            $table->decimal('harga_beli', 15, 2);
            
        });
    }
};