<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPembelian extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'pembelian_id',
        'product_id',
        'jumlah',
        'harga_beli',
    ];

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}