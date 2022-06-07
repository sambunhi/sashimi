<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Source;
use App\Models\Trend;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class GetTrendsTest extends TestCase
{
    use RefreshDatabase;

    public function testStatusShouldBe200(): void
    {
        $this->getResponse([
                'date_start' => '2022-01-01',
                'date_end' => '2022-06-10',
            ])
            ->assertStatus(200);
    }

    private function getResponse(array $params = []): TestResponse
    {
        return $this->json('GET', '/api/v1/trends', $params);
    }

    public function testStatusShouldBe422ForMissingDateRange(): void
    {
        $this->getResponse()->assertStatus(422);
    }

    public function testKeywordsFilter(): void
    {
        $expected1 = 'expected1';
        $expected2 = 'expected2';
        $unexpected = 'unexpected';

        /** @var Article */
        $article = Article::factory()
            ->has(Trend::factory()->state(['keyword' => $expected1]), 'trend')
            ->has(Trend::factory()->state(['keyword' => $expected2]), 'trend')
            ->has(Trend::factory()->state(['keyword' => $unexpected]), 'trend')
            ->analyzed()
            ->create();

        $response = $this->getResponse([
            'date_start' => $article->published_at->subDay()->toDateString(),
            'date_end' => now()->toDateString(),
            'keywords' => "$expected1,$expected2"
        ]);

        $response->assertJsonFragment([
                'keyword' => $expected1,
            ])
            ->assertJsonFragment([
                'keyword' => $expected2,
            ])
            ->assertJsonMissing([
                'keyword' => $unexpected,
            ]);
    }

    public function testSourcesFilter(): void
    {
        $expected1 = Source::factory()->create();
        $expected2 = Source::factory()->create();
        $unexpected = Source::factory()->create();

        $date = now()->subDay();
        $keyword = Str::random();

        /** @var Factory */
        $articleFactory = Article::factory()
            ->has(Trend::factory()->state(['keyword' => $keyword]), 'trend')
            ->state(['published_at' => $date])
            ->analyzed();

        /** @var Article */
        $article1 = $articleFactory->for($expected1)->create();
        /** @var Article */
        $article2 = $articleFactory->for($expected2)->create();
        $articleFactory->for($unexpected)->create();

        $response = $this->getResponse([
            'date_start' => $date->toDateString(),
            'date_end' => $date->toDateString(),
            'keywords' => $keyword,
            'sources' => $expected1->getKey() . ',' . $expected2->getKey()
        ]);

        $response->assertJson(fn (AssertableJson $json) =>
            $json->where(
                'trends.0.cnt',
                $article1->trend->count() + $article2->trend->count()
            )
        );
    }
}
