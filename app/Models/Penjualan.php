<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    /**
     * DIUBAH: Menambahkan 'nomor_invoice' agar bisa diisi secara massal
     */
    protected $fillable = [
        'nomor_invoice', // <-- INI YANG DITAMBAHKAN
        'pelanggan',
        'total_harga',
        'user_id'
    ];

    /**
     * Relasi ke PenjualanDetail
     */
    public function details()
    {
        return $this->hasMany(PenjualanDetail::class);
    }

    /**
     * Relasi ke User (Penjaga Toko/Kasir)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
