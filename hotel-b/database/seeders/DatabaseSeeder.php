<?php
namespace Database\Seeders;

use App\Models\Kamar;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['Melati Inn - Standar', 'Surabaya', 300000, 2],
            ['Melati Inn - Suite', 'Surabaya', 900000, 3],
            ['Melati Malang - Superior', 'Malang', 500000, 2],
        ] as [$n, $k, $h, $c]) {
            Kamar::create(['nama_kamar' => $n, 'kota' => $k, 'harga_per_malam' => $h, 'kapasitas' => $c]);
        }
    }
}
