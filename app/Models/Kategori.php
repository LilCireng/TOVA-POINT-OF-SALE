<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi.
     */
    protected $fillable = ['nama_kategori'];

    /**
     * Relasi ke model Barang.
     */
    public function barangs()
    {
        // Sesuaikan 'kategori_id' jika nama kolom foreign key Anda berbeda
        return $this->hasMany(Barang::class, 'id_kategori');
    }
}