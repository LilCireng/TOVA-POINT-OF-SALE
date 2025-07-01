<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('detail_penjualan', function (Blueprint $table) {
            $table->id(); 
            $table->foreignId('penjualan_id')->constrained('penjualans')->onDelete('cascade');
            $table->foreignId('barang_id')->nullable()->constrained('barangs')->onDelete('set null');
            $table->integer('jumlah'); 
            $table->decimal('harga_satuan', 15, 2); 
            $table->decimal('subtotal', 15, 2); 
            $table->timestamps(); 
        });
    }

    public function down()
    {
        Schema::dropIfExists('detail_penjualan');
    }
};