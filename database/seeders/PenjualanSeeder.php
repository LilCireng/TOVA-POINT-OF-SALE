<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Penjualan;

class PenjualanSeeder extends Seeder
{
    public function run(): void
    {
        Penjualan::create([
            'nomor_invoice' => 'INV-SEED-001',
            'total_harga' => 80000,
            'created_at' => now()->subDays(1),
            'updated_at' => now()->subDays(1),
        ]);

        Penjualan::create([
            'nomor_invoice' => 'INV-SEED-002',
            'total_harga' => 125000,
            'created_at' => now()->subDays(2),
            'updated_at' => now()->subDays(2),
        ]);

        Penjualan::create([
            'nomor_invoice' => 'INV-SEED-003',
            'total_harga' => 60000,
            'created_at' => now()->subDays(4),
            'updated_at' => now()->subDays(4),
        ]);
    }
}