<?php

namespace Database\Seeders;

use App\Models\Influencer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InfluencerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Influencer::create([
            'name' => 'John Doe',
            'ig_user' => '@johndoe',
            'followers' => 10000,
            'category' => 'Tech',
        ]);

        Influencer::factory()->count(9)->create();
    }
}
