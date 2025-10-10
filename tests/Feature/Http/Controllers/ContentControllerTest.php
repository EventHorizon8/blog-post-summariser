<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use App\Models\ContentSummary;
use App\Services\AIClient\AIClientInterface;
use App\Services\Client\ClientScraperInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Mockery;
use Tests\TestCase;

class ContentControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->clientScraper = Mockery::mock(ClientScraperInterface::class);
        $this->aiClient = Mockery::mock(AIClientInterface::class);

        App::instance(ClientScraperInterface::class, $this->clientScraper);
        App::instance(AIClientInterface::class, $this->aiClient);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_returns_existing_summary()
    {
        $summary = ContentSummary::factory()->create([
            'url' => 'http://test.com',
            'summary' => 'Existing summary',
        ]);

        $response = $this->postJson('/api/summaries', ['url' => 'http://test.com']);

        $response->assertOk()
            ->assertJsonFragment(['summary' => 'Existing summary']);
    }

    public function test_returns_summary_when_only_original_content_exists()
    {
        ContentSummary::factory()->create([
            'url' => 'http://test.com',
            'original_content' => 'Some content',
            'summary' => null,
        ]);

        $this->aiClient->shouldReceive('summarizeContent')
            ->with('Some content')
            ->andReturn('AI summary');
        $this->aiClient->shouldReceive('getTotalTokensPrevRequest')
            ->andReturn(42);

        $response = $this->postJson('/api/summaries', ['url' => 'http://test.com']);

        $response->assertOk()
            ->assertJsonFragment(['summary' => 'AI summary', 'token_count' => 42]);
    }

    public function test_fetches_and_summarizes_new_content()
    {
        $this->clientScraper->shouldReceive('getContent')
            ->with('http://test.com')
            ->andReturn('<!DOCTYPE html><html>Parsed content</html>');
        $this->aiClient->shouldReceive('summarizeContent')
            ->with('Parsed content')
            ->andReturn('AI summary');
        $this->aiClient->shouldReceive('getTotalTokensPrevRequest')
            ->andReturn(42);

        $response = $this->postJson('/api/summaries', ['url' => 'http://test.com']);

        $response->assertOk()
            ->assertJsonFragment(['summary' => 'AI summary', 'token_count' => 42]);
        $this->assertDatabaseHas('content_summaries', [
            'url' => 'http://test.com',
            'original_content' => 'Parsed content',
            'summary' => 'AI summary',
            'token_count' => 42,
        ]);
    }

    public function test_index_returns_paginated_summaries()
    {
        ContentSummary::factory()->count(5)->create();
        $response = $this->getJson('/api/summaries');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'last_page',
            ]);
        $this->assertCount(3, $response->json('data'));
    }
}
