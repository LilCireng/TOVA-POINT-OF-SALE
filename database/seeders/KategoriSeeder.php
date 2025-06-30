<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kategori; // <-- Jangan lupa import Model Kategori

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Daftar kategori awal yang ingin kita masukkan
        $kategoris = [
            ['nama_kategori' => 'Device'],
            ['nama_kategori' => 'Liquid'],
            ['nama_kategori' => 'Cartridge'],
            ['nama_kategori' => 'Coil'],
            ['nama_kategori' => 'Kapas'],
            ['nama_kategori' => 'Baterai'],
        ];

        // Looping untuk memasukkan setiap kategori ke dalam database
        foreach ($kategoris as $kategori) {
            Kategori::create($kategori);
        }
    }
}