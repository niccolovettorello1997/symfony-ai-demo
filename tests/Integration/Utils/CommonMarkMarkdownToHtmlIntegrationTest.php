<?php

declare(strict_types=1);

namespace Tests\Integration\Utils;

use App\Utils\CommonMarkMarkdownToHtml;
use League\CommonMark\CommonMarkConverter;
use PHPUnit\Framework\TestCase;

class CommonMarkMarkdownToHtmlIntegrationTest extends TestCase
{
    protected function setUp(): void
    {
        if (!getenv('INTEGRATION_TESTS')) {
            self::markTestSkipped('Integration tests disabled. Set INTEGRATION_TESTS=1 to enable.');
        }
    }

    public function test_converts_markdown_to_html(): void
    {
        $converter = new CommonMarkMarkdownToHtml(
            new CommonMarkConverter()
        );

        $html = $converter->convert('**bold**');

        self::assertStringContainsString('<strong>bold</strong>', $html);
    }
}
