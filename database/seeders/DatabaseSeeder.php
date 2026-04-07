<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Kategori;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Admin::firstOrCreate(
            ['username' => 'admin'],
            ['password' => Hash::make('password')]
        );

        foreach (['Sarana dan Prasarana', 'Kebersihan', 'Keamanan', 'Pembelajaran'] as $namaKategori) {
            Kategori::firstOrCreate([
                'ket_kategori' => $namaKategori,
            ]);
        }
    }
}
