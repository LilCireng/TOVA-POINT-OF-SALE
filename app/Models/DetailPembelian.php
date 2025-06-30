<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPembelian extends Model
{
    use HasFactory;

    /**
     * Kita tidak perlu timestamps (created_at, updated_at) untuk detail transaksi.
     */
    public $timestamps = false;

    /**
     * Kolom-kolom yang boleh diisi secara massal.
     */
    protected $fillable = [
        'pembelian_id',
        'product_id',
        'jumlah',
        'harga_beli',
    ];

    /**
     * Mendefinisikan relasi bahwa detail ini milik satu Pembelian.
     */
    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class);
    }

    /**
     * Mendefinisikan relasi bahwa detail ini merujuk ke satu Product (Barang).
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}