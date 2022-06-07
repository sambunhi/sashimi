<?php

namespace Database\Factories;

use App\Models\Source;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(),
            'url' => $this->faker->url(),
            'source_id' => Source::factory(),
            'published_at' => $this->faker->dateTimeThisCentury(),
        ];
    }

    public function analyzed()
    {
        return $this->state(function (array $attributes) {
            return [
                'nltk_at' => $this->faker->dateTimeBetween($attributes['published_at']),
            ];
        });
    }
}
