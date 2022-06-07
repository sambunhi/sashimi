<?php

namespace Database\Factories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Trend>
 */
class TrendFactory extends Factory
{
    public function definition()
    {
        return [
            'article_id' => Article::factory(),
            'keyword' => $this->faker->word(),
            'cnt' => rand(1, 100),
        ];
    }
}
