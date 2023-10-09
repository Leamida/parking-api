<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ParkingBlock;
use App\Models\ParkingSlot;

class ParkingSeeder extends Seeder
{
    public function run()
    {
        // Seed data blok parkir
        ParkingBlock::create(['name' => 'Blok A', 'total_slots' => 10]);
        ParkingBlock::create(['name' => 'Blok B', 'total_slots' => 15]);
        ParkingBlock::create(['name' => 'Blok C', 'total_slots' => 20]);

        // Seed data slot parkir di setiap blok
        $blocks = ParkingBlock::all();

        foreach ($blocks as $block) {
            for ($i = 1; $i <= $block->total_slots; $i++) {
                ParkingSlot::create(['parking_block_id' => $block->id]);
            }
        }
    }
}
