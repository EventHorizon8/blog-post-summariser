<?php

declare(strict_types=1);

namespace App\Services\Parser;

use DOMDocument;
use DOMXPath;
use fivefilters\Readability\Configuration;
use fivefilters\Readability\ParseException;
use fivefilters\Readability\Readability;
use Illuminate\Support\Facades\Log;

/**
 * Class ContentParser
 */
class ContentParser
{
    /**
     * Clean the HTML content to extract the main text (e.g., remove ads, navigation, scripts etc.).
     * @param string $content
     * @return string
     */
    public function getHtmlToPlainText(string $content): string
    {
        try {
            $preparedText = $this->getContentByReadability($content);
            if ($preparedText) {
                return $preparedText;
            }
        } catch (\Throwable $e) {
            Log::notice($e->getMessage(), ['exception' => $e]);
        }
        return $this->largestTextBlock($content);
    }

    /**
     * Use Readability library to extract main content.
     * @param string $content
     * @return string
     * @throws ParseException
     */
    private function getContentByReadability(string $content): string
    {
        $config = new Configuration();
        $config->setFixRelativeURLs(true);
        $readability = new Readability($config);
        $readability->parse($content);
        $contentHtml = $readability->getContent();
        return $contentHtml ? trim(
            preg_replace('/\s+/', ' ', strip_tags(preg_replace('/><(\/?\w+)/', '> <$1', $contentHtml)))
        ) : '';
    }


    /**
     * Plan B method when Readability fails: find the largest text block in the HTML.
     * @param string $html
     * @return string
     */
    private function largestTextBlock(string $html): string
    {
        libxml_use_internal_errors(true);
        $doc = new DOMDocument();
        $doc->loadHTML('<?xml encoding="utf-8" ?>' . $html);

        $xpath = new DOMXPath($doc);
        //remove script, style, noscript, footer, nav nodes
        foreach ($xpath->query('//script|//style|//noscript|//footer|//nav') as $n) {
            $n->parentNode->removeChild($n);
        }

        $textNodes = $xpath->query('//text()');

        $textParts = [];
        foreach ($textNodes as $node) {
            $text = trim($node->nodeValue);
            if ($text !== '') {
                $textParts[] = $text;
            }
        }
        return trim(preg_replace('/\s+/', ' ', implode(' ', $textParts)));
    }
}
