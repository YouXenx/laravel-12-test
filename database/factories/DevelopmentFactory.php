<?php

namespace Database\Factories;

use App\Models\Development;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Development>
 */
class DevelopmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

     public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['Pembanguanan Jalan', 'Perbaikan Jalan', 'Pembuatan Jalan']) . ' ' . $this->faker->city,
            'thumbnail' => $this->faker->imageUrl(),
            'description' => $this->faker->paragraph(),
            'person_in_charge' => $this->faker->name(),
            'start_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'end_date' => $this->faker->dateTimeBetween('now', '+1 year'),
            'amount' => $this->faker->randomFloat(2, 10000, 100000),
            'status' => $this->faker->randomElement(['ongoing', 'completed']),
        ];
    }
}