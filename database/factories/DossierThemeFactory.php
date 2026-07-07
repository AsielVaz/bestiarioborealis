<?php

namespace Database\Factories;

use App\Models\DossierTheme;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DossierTheme>
 */
class DossierThemeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'key' => fake()->unique()->slug(2),
            'name' => fake()->words(2, true),
            'description' => fake()->sentence(),
            'primary_color' => '#4c1d95',
            'accent_color' => '#d6ad60',
            'parchment_tone' => '#ead8b7',
            'is_active' => true,
        ];
    }
}
