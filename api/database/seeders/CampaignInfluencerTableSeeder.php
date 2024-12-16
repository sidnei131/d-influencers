<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\Influencer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CampaignInfluencerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $campaigns = Campaign::all();
        $influencers = Influencer::all();

        foreach ($campaigns as $campaign) {
            $campaign->influencers()->attach(
                $influencers->random(rand(1, $influencers->count()))->pluck('id')->mapWithKeys(function ($id) {
                    return [
                        $id => [
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    ];
                })->toArray()
            );
        }
    }
}
