<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Parser;

use App\Services\Parser\ContentParser;
use Illuminate\Support\Facades\Log;
use Mockery;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

#[CoversMethod(ContentParser::class, 'getHtmlToPlainText')]
class ContentParserTest extends TestCase
{
    /**
     * @param string $html
     * @param string $expected
     * @return void
     */
    #[DataProvider('dataProvider')]
    public function test_html_to_plain_text(string $html, string $expected): void
    {
        $parser = new ContentParser;
        $this->assertStringContainsString($expected, $parser->getHtmlToPlainText($html));
    }


    public function test_getHtmlToPlainText_logs_exception_when_readability_fails(): void
    {
        Log::shouldReceive('notice')
            ->once()
            ->with(Mockery::type('string'), Mockery::type('array'));

        $parser = new ContentParser;
        $parser->getHtmlToPlainText('<p>Test</p><script>throw new Error("Test error")</script>');
    }

    public static function dataProvider(): array
    {
        return [
            [
                '<!DOCTYPE html><html><body><h1>Title</h1><p>This is a <strong>test</strong></p></body></html>',
                'Title This is a test'
            ],
            [
                '<!DOCTYPE html><html><body><div><h1>Title</h1><p>Paragraph with <a href="#">link</a></p></div></body></html>',
                'Title Paragraph with link'
            ],
            ['<!DOCTYPE html><html><body><ul><li>Item 1</li><li>Item 2</li></ul></body></html>', 'Item 1 Item 2'],
            ['<!DOCTYPE html><html><body><p>   Extra    spaces   </p></body></html>', 'Extra spaces'],
            [
                '<!DOCTYPE html><html><head><script type="text/javascript"></script></head><script>console.log("test");</script><body><p>Text with script</p></body></html>',
                'Text with script'
            ],
        ];
    }
}
