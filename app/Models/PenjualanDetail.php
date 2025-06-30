<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanDetail extends Model
{
    use HasFactory;
    
    // Nama tabel yang akan digunakan oleh model ini
    protected $table = 'detail_penjualan';

    // Kolom yang boleh diisi secara massal
    protected $fillable = [
        'penjualan_id',
        'barang_id',
        'jumlah',
        'harga_satuan',
        'subtotal'
    ];

    // DIUBAH: Baris ini dihapus atau diubah menjadi true karena tabelnya sudah punya timestamps
    // public $timestamps = false;
    // Jika baris di atas ada, hapus saja. Jika tidak ada, biarkan.

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class);
    }
}
