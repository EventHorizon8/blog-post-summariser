<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ContentSummary>
 */
class ContentSummaryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'url' => $this->faker->url,
            'original_content' => $this->faker->paragraph,
            'summary' => $this->faker->sentence,
            'token_count' => $this->faker->numberBetween(10, 100),
        ];
    }
}
