<?php

declare(strict_types=1);

namespace App\Services\Client;

/**
 * Interface ClientCrawlerInterface
 * Defines the contract for a client crawler service.
 */
interface ClientScraperInterface
{
    /**
     * Fetches the content of the given URL.
     * @param string $url
     * @return string
     */
    public function getContent(string $url): string;
}
