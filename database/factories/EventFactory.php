<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'thumbnail' => $this->faker->imageUrl(640, 480, 'event', true),
            'name' => $this->faker->randomElement(['Belajar Bahasa Inggris', 'Jalan Sehat', 'kerja Bakti']) . ' ' . $this->faker->city,
            'description' => $this->faker->sentence,
            'price' => $this->faker->randomFloat(2, 100000, 1000000),
            'date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'time' => $this->faker->time(),
            'is_active' => $this->faker->boolean(),

        ];
    }
}
