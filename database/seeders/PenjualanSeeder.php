<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Penjualan;

class PenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data Penjualan 1 (dibuat 1 hari yang lalu)
        Penjualan::create([
            'nomor_invoice' => 'INV-SEED-001',
            'total_harga' => 80000,
            // 'bayar' dan 'kembalian' DIHAPUS karena tidak ada di tabel Anda
            'created_at' => now()->subDays(1),
            'updated_at' => now()->subDays(1),
        ]);

        // Data Penjualan 2 (dibuat 2 hari yang lalu)
        Penjualan::create([
            'nomor_invoice' => 'INV-SEED-002',
            'total_harga' => 125000,
            'created_at' => now()->subDays(2),
            'updated_at' => now()->subDays(2),
        ]);

        // Data Penjualan 3 (dibuat 4 hari yang lalu)
        Penjualan::create([
            'nomor_invoice' => 'INV-SEED-003',
            'total_harga' => 60000,
            'created_at' => now()->subDays(4),
            'updated_at' => now()->subDays(4),
        ]);
    }
}