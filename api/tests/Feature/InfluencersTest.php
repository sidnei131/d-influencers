<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Campaign;
use App\Models\Influencer;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InfluencersTest extends TestCase
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

    public function test_can_list_all_influencers(): void
    {
        Influencer::factory()->count(5)->create();

        $response = $this->getJson(route('influencers.index'), $this->authHeader());

        $response->assertStatus(200);
        $response->assertJsonCount(5, 'data');
    }

    public function test_can_view_a_single_influencer(): void
    {
        $influencer = Influencer::factory()->create();

        $response = $this->getJson(route('influencers.show', $influencer->id), $this->authHeader());

        $response->assertStatus(200);
        $response->assertJson(['data' => [
            'id' => $influencer->id,
            'name' => $influencer->name,
            'ig_user' => $influencer->ig_user,
        ]]);
    }

    public function test_can_create_an_influencer(): void
    {
        $data = [
            'name' => 'Test Name',
            'ig_user' => '@test-ig-user',
            'followers' => 10000,
            'category' => 'Tech',
        ];

        $response = $this->postJson(route('influencers.store'), $data, $this->authHeader());

        $response->assertStatus(201);
        $this->assertDatabaseHas('influencers', ['ig_user' => '@test-ig-user']);
    }

    public function test_can_create_influencer_with_campaigns(): void
    {
        $campaigns = Campaign::factory()->count(3)->create();

        $data = [
            'name' => 'New Influencer',
            'ig_user' => '@new_influencer',
            'followers' => 5000,
            'category' => 'Test',
            'campaigns' => $campaigns->pluck('id')->toArray(),
        ];

        $response = $this->postJson(route('influencers.store'), $data, $this->authHeader());

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'id', 'name', 'ig_user', 'followers', 'category', 'campaigns' => [
                    ['id', 'name', 'init_date', 'end_date', 'budget']
                ],
            ]
        ]);

        $this->assertDatabaseHas('influencers', ['name' => 'New Influencer']);
        $this->assertDatabaseCount('campaign_influencer', 3);
    }

    public function test_can_update_an_influencer(): void
    {
        $influencer = Influencer::factory()->create();

        $data = ['name' => 'Updated Name', 'followers' => 20000];

        $response = $this->putJson(route('influencers.update', $influencer->id), $data, $this->authHeader());

        $response->assertStatus(200);
        $this->assertDatabaseHas('influencers', ['name' => 'Updated Name']);
    }

    public function test_can_delete_an_influencer(): void
    {
        $influencer = Influencer::factory()->create();

        $response = $this->deleteJson(route('influencers.destroy', $influencer->id), [], $this->authHeader());

        $response->assertStatus(200);
        $this->assertSoftDeleted('influencers', ['id' => $influencer->id]);
    }

    public function test_validation_error_on_create(): void
    {
        $data = [
            'name' => '',
            'ig_user' => '',
            'followers' => -1,
        ];

        $response = $this->postJson(route('influencers.store'), $data, $this->authHeader());

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'ig_user', 'followers']);
    }

    public function test_can_create_influencer_with_same_ig_user_after_soft_delete(): void
    {
        $influencer = Influencer::factory()->create(['ig_user' => '@testing']);
        
        $response = $this->deleteJson(route('influencers.destroy', $influencer->id), [], $this->authHeader());
        $response->assertStatus(200);

        $this->assertSoftDeleted('influencers', ['id' => $influencer->id]);
        $exists = DB::table('influencers')
            ->where('ig_user', 'LIKE', '@testing--deleted-%')
            ->exists();
    
        $this->assertTrue($exists, 'Soft deleted influencer does not have the expected IG user format.');

        $response = $this->postJson(route('influencers.store'), [
            'name' => 'Testing',
            'ig_user' => '@testing',
            'followers' => 1000,
            'category' => 'Testing',
        ], $this->authHeader());

        $response->assertStatus(201);
        $this->assertDatabaseHas('influencers', ['ig_user' => '@testing']);
    }


    private function authHeader(): array
    {
        return [
            'Authorization' => "Bearer {$this->token}",
        ];
    }
}
