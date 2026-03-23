<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\SocialAssistance;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SocialAssistance>
 */
class SocialAssistanceFactory extends Factory
{
    protected $model = SocialAssistance::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'thumbnail' => $this->faker->imageUrl(640, 480, 'people', true),

            'name' => $this->faker->randomElement([
                'Bantuan Pangan',
                'Bantuan Kesehatan',
                'Bantuan Pendidikan'
            ]) . ' ' . $this->faker->unique()->numberBetween(1, 100),

            'category' => $this->faker->randomElement([
                'staple',
                'cash',
                'health'   
                     ]),

            'amount' => $this->faker->randomFloat(2, 10000, 1000000),

            'provider' => $this->faker->company(),

            'description' => $this->faker->sentence(),

            'is_available' => $this->faker->boolean(),
        ];
    }
}