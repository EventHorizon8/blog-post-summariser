<?php

declare(strict_types=1);

namespace App\Services\AIClient;

use OpenAI\Laravel\Facades\OpenAI;

/**
 * Class OpenAIClient
 */
class OpenAIClient implements AIClientInterface
{
    private const MODEL = 'gpt-4o-mini';

    private int $totalTokensPrevRequest = 0;

    public function summarizeContent(string $content): string
    {
        $response = OpenAI::responses()->create([
            'model' => self::MODEL,
            'input' => [
                [
                    'role' => 'system',
                    'content' => 'You are a helpful assistant that creates concise, informative summaries of blog posts and articles.'
                ],
                [
                    'role' => 'user',
                    'content' => $content
                ]
            ],
        ]);

        $this->totalTokensPrevRequest = $response->usage->totalTokens;
        return $response->outputText;
    }

    public function getTotalTokensPrevRequest(): int
    {
        return $this->totalTokensPrevRequest;
    }
}
