<?php

namespace Database\Seeders;

use App\Models\Campaign;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CampaignsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Campaign::create([
            'name' => 'Summer Campaign',
            'budget' => 50000.00,
            'desc' => 'Promoting summer collection.',
            'init_date' => now(),
            'end_date' => now()->addDays(30),
        ]);

        Campaign::create([
            'name' => 'Winter Campaign',
            'budget' => 8000.00,
            'desc' => 'Promoting winter collection.',
            'init_date' => now(),
            'end_date' => now()->addDays(60),
        ]);

        Campaign::factory()->count(3)->create();
    }
}
