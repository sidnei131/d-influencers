<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Campaign;
use App\Models\Influencer;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CampaignsTest extends TestCase
{
    use RefreshDatabase;

    private string $token;

    protected function setUp(): void
    {
        parent::setUp();

        User::factory()->create([
            'email' => 'test@mail.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->postJson(route('login'), [
            'email' => 'test@mail.com',
            'password' => 'password',
        ]);

        $this->token = $response->json('token');

    }
    
    private function authHeader(): array
    {
        return [
            'Authorization' => "Bearer {$this->token}",
        ];
    }
    
    public function test_can_list_all_campaigns(): void
    {
        Campaign::factory()->count(5)->create();

        $response = $this->getJson(route('campaigns.index'), $this->authHeader());

        $response->assertStatus(200);
        $response->assertJsonCount(5, 'data');
        $response->assertJsonStructure([
        'data' => [
            '*' => [
                'id',
                'name',
                'budget',
                'desc',
                'init_date',
                'end_date',
                'influencers',
            ],
        ]]);
    }

    public function test_can_view_a_single_campaign(): void
    {
        $campaign = Campaign::factory()->create();

        $response = $this->getJson(route('campaigns.show', $campaign->id), $this->authHeader());

        $response->assertStatus(200);
        $response->assertJson(['data' => [
            'id' => $campaign->id,
            'name' => $campaign->name,
        ]]);
    }

    public function test_can_create_a_campaign(): void
    {
        $data = [
            'name' => 'Summer Campaign',
            'budget' => 10000.00,
            'desc' => 'Promoting summer collection',
            'init_date' => now()->toDateString(),
            'end_date' => now()->addDays(30)->toDateString(),
        ];

        $response = $this->postJson(route('campaigns.store'), $data, $this->authHeader());

        $response->assertStatus(201);
        $this->assertDatabaseHas('campaigns', ['name' => 'Summer Campaign']);
    }

    public function test_can_create_campaign_with_influencers(): void
    {
        $influencers = Influencer::factory()->count(3)->create();

        $data = [
            'name' => 'New Campaign',
            'budget' => 5000.00,
            'desc' => 'Test campaign',
            'init_date' => now()->toDateString(),
            'end_date' => now()->addDays(30)->toDateString(),
            'influencers' => $influencers->pluck('id')->toArray(),
        ];

        $response = $this->postJson(route('campaigns.store'), $data, $this->authHeader());

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'id', 'name', 'budget', 'desc', 'init_date', 'end_date', 'influencers' => [
                    ['id', 'name', 'ig_user', 'followers']
                ],
            ]
        ]);

        $this->assertDatabaseHas('campaigns', ['name' => 'New Campaign']);
        $this->assertDatabaseCount('campaign_influencer', 3);
    }


    public function test_can_update_a_campaign(): void
    {
        $campaign = Campaign::factory()->create();

        $data = [
            'name' => 'Updated Campaign Name',
            'budget' => 15000.00,
        ];

        $response = $this->putJson(route('campaigns.update', $campaign->id), $data, $this->authHeader());

        $response->assertStatus(200);
        $this->assertDatabaseHas('campaigns', ['name' => 'Updated Campaign Name']);
    }

    public function test_can_delete_a_campaign(): void
    {
        $campaign = Campaign::factory()->create();

        $response = $this->deleteJson(route('campaigns.destroy', $campaign->id), $this->authHeader());

        $response->assertStatus(200);
        $this->assertSoftDeleted('campaigns', ['id' => $campaign->id]);
    }

    public function test_validation_error_on_create(): void
    {
        $data = [
            'name' => '',
            'budget' => -100,
        ];

        $response = $this->postJson(route('campaigns.store'), $data, $this->authHeader());

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'budget', 'init_date', 'end_date']);
    }
}
