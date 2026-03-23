<?php

namespace Database\Factories;

use App\Models\SocialAssistanceRecipient;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\SocialAssistance;
use App\Models\HeadOfFamily;

/**
 * @extends Factory<SocialAssistanceRecipient>
 */
class SocialAssistanceRecipientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
{
    return [
        'social_assistance_id' => SocialAssistance::factory(),
        'head_of_family_id' => HeadOfFamily::factory(),
        'amount' => fake()->numberBetween(100000, 1000000),
        'reason' => fake()->sentence(),
        'bank' => fake()->randomElement(['bri','bca','bni','mandiri']),
        'account_number' => fake()->unique()->numberBetween(100000000,999999999),
        'proof' => fake()->imageUrl(),
        'status' => $this->faker->randomElement(['pending','approved','rejected']),
    ];
}
}
