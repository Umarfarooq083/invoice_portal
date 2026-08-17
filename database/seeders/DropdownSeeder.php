<?php

namespace Database\Seeders;

use App\Models\AppType;
use App\Models\Block;
use App\Models\Phase;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DropdownSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $blocks = ['Block A', 'Block B', 'Block C', 'Block D', 'Block E'];
        foreach ($blocks as $name) {
            Block::firstOrCreate(['name' => $name]);
        }

        $phases = ['Phase 1', 'Phase 2', 'Phase 3', 'Phase 4', 'Phase 5'];
        foreach ($phases as $name) {
            Phase::firstOrCreate(['name' => $name]);
        }

        $appTypes = ['New Booking', 'Transfer', 'Exchange'];
        foreach ($appTypes as $name) {
            AppType::firstOrCreate(['name' => $name]);
        }
    }
}
