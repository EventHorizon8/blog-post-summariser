<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\SummarizeRequest;
use App\Models\ContentSummary;
use App\Services\AIClient\AIClientInterface;
use App\Services\Client\ClientScraperInterface;
use App\Services\Parser\ContentParser;
use Illuminate\Http\JsonResponse;

class ContentController extends Controller
{
    private const int SUMMARIES_PER_PAGE = 3;

    public function __construct(
        private readonly ClientScraperInterface $clientScraper,
        private readonly AIClientInterface $aiClient,
    ) {
    }

    /**
     * The list of summaries
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $summaries = ContentSummary::orderBy('created_at', 'desc')->paginate(self::SUMMARIES_PER_PAGE);
        return response()->json($summaries);
    }

    /**
     * Summarize the content by url
     * @param SummarizeRequest $request
     * @return JsonResponse
     */
    public function summarize(SummarizeRequest $request): JsonResponse
    {
        $url = $request->input('url');
        // if we already have this url in db, return the summary
        $contentSummary = ContentSummary::where('url', $url)->first();
        if ($contentSummary?->summary) {
            return response()->json($contentSummary);
        }

        if ($contentSummary?->original_content) {
            $plainText = $contentSummary->original_content;
        } else {
            $content = $this->clientScraper->getContent($url);
            $plainText = (new ContentParser)->getHtmlToPlainText($content);

            if (!$plainText) {
                return response()->json(['message' => 'Failed to extract content from the URL.'], 422);
            }

            $contentSummary = new ContentSummary();
            $contentSummary->url = $url;
            $contentSummary->original_content = $plainText;

            $contentSummary->save();
        }
        //todo: can take long time
        try {
            $summary = $this->aiClient->summarizeContent($plainText);
        } catch (\Throwable $exception) {
            return response()->json(['message' => 'Failed to summarize content. ' . $exception->getMessage()], 500);
        }

        if ($summary) {
            $contentSummary->summary = $summary;
            $contentSummary->token_count = $this->aiClient->getTotalTokensPrevRequest();
            $contentSummary->save();
        }

        return response()->json($contentSummary);
    }
}
