<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * ===============================================================
     * INI ADALAH BARIS PENTING YANG HILANG.
     * Baris ini menghubungkan Model 'Product' dengan tabel 'barangs'.
     * ===============================================================
     */
    protected $table = 'barangs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'id_kategori',
        'harga_beli',
        'harga_jual',
        'stok',
    ];
}