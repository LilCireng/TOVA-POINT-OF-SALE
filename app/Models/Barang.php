<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $fillable = 
    [
        'nama',
        'id_kategori',
        'harga_beli', 
        'harga_jual', 
        'stok',
    ];
    
    public function stokMasuk() { return $this->hasMany(StokMasuk::class); }
    public function stokKeluar() { return $this->hasMany(StokKeluar::class); }
    public function penjualan() { return $this->hasMany(Penjualan::class); }
    public function kategori() { return $this->belongsTo(Kategori::class, 'id_kategori'); }

    public function getMarginAttribute()
    {
        return $this->harga_jual - $this->harga_beli;
    }
}