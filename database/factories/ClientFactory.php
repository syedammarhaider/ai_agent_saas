<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::factory()->createQuietly();
        
        return [
'user_id' => $user->id,
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'company' => fake()->company(),
            'status' => fake()->randomElement(['active', 'inactive']),
            'platforms' => json_encode(['whatsapp', 'email']),
            'total_revenue' => fake()->randomFloat(2, 0, 10000),
            'notes' => fake()->sentence(),
        ];
    }
}

