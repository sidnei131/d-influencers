<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Influencer>
 */
class InfluencerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'ig_user' => '@' . $this->faker->userName,
            'followers' => $this->faker->numberBetween(1000, 2000000),
            'category' => $this->faker->randomElement(['Tech', 'Beauty', 'Fitness', 'Gaming', 'Health']),
        ];
    }
}
