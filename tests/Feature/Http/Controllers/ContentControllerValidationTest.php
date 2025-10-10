<?php

declare(strict_types=1);

namespace Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContentControllerValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_summarize_requires_url()
    {
        $response = $this->postJson('/api/summaries', []);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['url']);
    }

    public function test_summarize_requires_valid_url()
    {
        $response = $this->postJson('/api/summaries', ['url' => 'not-a-url']);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['url']);
    }
}
