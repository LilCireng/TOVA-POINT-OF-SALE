<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    /**
     * DIUBAH: Sesuaikan dengan kolom di database (harga_beli & harga_jual)
     */
    protected $fillable = 
    [
        'nama',
        'id_kategori',
        'harga_beli', // Menggunakan harga_beli
        'harga_jual', // Menggunakan harga_jual
        'stok',
    ];
    
    // Relasi yang sudah ada (tidak perlu diubah)
    public function stokMasuk() { return $this->hasMany(StokMasuk::class); }
    public function stokKeluar() { return $this->hasMany(StokKeluar::class); }
    public function penjualan() { return $this->hasMany(Penjualan::class); }
    public function kategori() { return $this->belongsTo(Kategori::class, 'id_kategori'); }

    /**
     * DITAMBAHKAN: Accessor untuk menghitung Margin secara otomatis.
     * Sekarang Anda bisa memanggil $barang->margin di view.
     */
    public function getMarginAttribute()
    {
        // Margin adalah selisih antara harga jual dan harga beli
        return $this->harga_jual - $this->harga_beli;
    }
}