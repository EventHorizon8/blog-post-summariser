<?php

declare(strict_types=1);

namespace App\Services\AIClient;

interface AIClientInterface
{
    /**
     * Summarize the given content using an AI model.
     * @param string $content
     * @return string
     */
    public function summarizeContent(string $content): string;

    /**
     * Get the total number of tokens used in the previous request.
     * @return int
     */
    public function getTotalTokensPrevRequest(): int;
}
