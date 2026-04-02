<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Client;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Conversation>
 */
class ConversationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::factory()->createQuietly();
        $client = Client::factory()->createQuietly();
        
        return [
            'user_id' => $user->id,
            'client_id' => $client->id,
            'title' => fake()->sentence(4),
            'status' => fake()->randomElement(['active', 'completed', 'archived']),
            'started_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}

