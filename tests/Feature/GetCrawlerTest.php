<?php

namespace Tests\Feature;

use App\Models\Keyword;
use App\Models\Source;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class GetCrawlerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var Collection<Source>
     */
    private Collection $sources;

    /**
     * @var Collection<Keyword>
     */
    private Collection $keywords;

    protected function setUp(): void
    {
        parent::setup();

        $this->sources = Source::factory()->count(5)->create();
        $this->keywords = Keyword::factory()->count(5)->create();
    }

    public function testStatusShouldBe200(): void
    {
        $this->getResponse()->assertStatus(200);
    }

    private function getResponse(): TestResponse
    {
        return $this->get('/api/v1/crawler');
    }

    public function testSources(): void
    {
        $this->getResponse()
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('sources', $this->sources->count())
                    ->etc()
            );
    }

    public function testKeywords(): void
    {
        $this->getResponse()
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('keywords', $this->keywords->pluck('name'))
                    ->etc()
            );
    }
}
