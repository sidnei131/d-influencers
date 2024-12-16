<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'John Doe',
            'email' => 'johndoe@mail.com',
            'password' => '@12345678'
        ]);
        
        User::factory(10)->create();

        $this->call([
            InfluencerTableSeeder::class,
            CampaignsTableSeeder::class,
            CampaignInfluencerTableSeeder::class,
        ]);
    }
}
