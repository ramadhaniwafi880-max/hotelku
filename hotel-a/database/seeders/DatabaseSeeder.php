<?php
namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['Grand Surabaya - Deluxe', 'Surabaya', 450000, 2],
            ['Grand Surabaya - Family', 'Surabaya', 750000, 4],
            ['Grand Bali - Ocean View', 'Bali', 1200000, 2],
        ] as [$n, $c, $p, $k]) {
            Room::create(['room_name' => $n, 'city' => $c, 'price_per_night' => $p, 'capacity' => $k]);
        }
    }
}
