<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Todo>
 */
class TodoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'done' => $this->faker->randomElement([true, false]),
        ];
    }

    public function expired(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'created_at' => now()->subDays(40),
                'updated_at' => now()->subDays(40),
            ];
        });
    }

    public function done(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'done' => true,
            ];
        });
    }

    public function notDone(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'done' => false,
            ];
        });
    }
}
