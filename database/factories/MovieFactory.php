<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Movie>
 */
class MovieFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'summary' => $this->faker->paragraph,
            'image' => 'default.jpg',
            'url' => $this->faker->url,
            'genres' => $this->faker->name,
            'author' => $this->faker->name,
            'tags' => $this->faker->words(3, true),
            'imdb_rating' => $this->faker->randomFloat(3, 4, 10),
            'pdf' => $this->faker->url . '/dummy.pdf',
            'user_id' => rand(1,10),
        ];
    }
}
