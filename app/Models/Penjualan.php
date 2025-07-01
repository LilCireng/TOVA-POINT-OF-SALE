<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_invoice', 
        'pelanggan',
        'total_harga',
        'user_id'
    ];

    public function details()
    {
        return $this->hasMany(PenjualanDetail::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}