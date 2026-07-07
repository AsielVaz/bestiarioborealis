<?php

namespace Database\Factories;

use App\Models\BestiaryEntry;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BestiaryEntry>
 */
class BestiaryEntryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->words(3, true),
            'slug' => fake()->unique()->slug(3),
            'classification' => fake()->randomElement(['Criatura', 'PNJ', 'Jefe']),
            'threat_level' => fake()->randomElement(['Baja', 'Media', 'Alta']),
            'description' => fake()->paragraph(),
        ];
    }
}
