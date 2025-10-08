<?php

declare(strict_types=1);

namespace App\Services\Client;

use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ClientCrawler
 * A service that fetches the content of a given URL.
 */
class ClientScraper implements ClientScraperInterface
{
    /**
     * @inheritDoc
     * @throws \Illuminate\Http\Client\ConnectionException
     */
    public function getContent(string $url): string
    {
        $response = Http::timeout(5)
            ->withoutVerifying()
            ->get($url);
        $body = $response->body();

        if ($response->getStatusCode() !== Response::HTTP_OK) {
            $response = Http::timeout(5)
                ->withoutVerifying()
                ->withHeaders([
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
                    'Accept-Encoding' => 'gzip, deflate, br, zstd',
                    'Connection' => 'keep-alive',
                    'sec-ch-device-memory' => '8',
                    'sec-ch-ua' => '"Not)A;Brand";v="99", "Google Chrome";v="127", "Chromium";v="127"',
                    'sec-ch-ua-arch' => '"arm"',
                    'sec-ch-ua-full-version-list' => '"Not)A;Brand";v="99.0.0.0", "Google Chrome";v="127.0.6533.120", "Chromium";v="127.0.6533.120"',
                    'sec-ch-ua-mobile' => '?0',
                    'sec-ch-ua-model' => '""',
                    'sec-ch-ua-platform' => '"macOS"',
                    'sec-fetch-dest' => 'document',
                    'sec-fetch-mode' => 'navigate',
                    'sec-fetch-site' => 'same-origin',
                    'sec-fetch-user' => '?1',
                    'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/127.0.0.0 Safari/537.36',
                    'priority' => 'u=0, i',
                    'cache-control' => 'max-age=0',
                    'accept-language' => 'en-GB,en-US;q=0.9,en;q=0.8,ru;q=0.7',
                ])
                ->get($url);
            $body = $response->body();
        }
        return $body;
    }
}
