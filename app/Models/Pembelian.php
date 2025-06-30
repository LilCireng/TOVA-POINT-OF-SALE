<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    use HasFactory;

    protected $fillable = ['supplier_id', 'no_faktur', 'tanggal', 'total'];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
