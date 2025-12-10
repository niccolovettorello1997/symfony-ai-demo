<?php

declare(strict_types=1);

namespace App\Tests\Unit\Utils;

use App\Utils\HTMLToTextConverter;
use PHPUnit\Framework\TestCase;

class HTMLToTextConverterTest extends TestCase
{
    public function test_converts_html_to_plain_text(): void
    {
        $html = '<p><strong>Hello</strong> world</p>';
        $expected = 'Hello world';

        $converter = new HTMLToTextConverter();

        self::assertSame($expected, $converter->toPlainText($html));
    }

    public function test_strips_whitespace_and_newlines(): void
    {
        $html = '<p>Hello     world</p>';
        $expected = 'Hello world';

        $converter = new HTMLToTextConverter();

        self::assertSame($expected, $converter->toPlainText($html));
    }

    public function test_returns_empty_string_for_empty_html(): void
    {
        $html = '';

        $converter = new HTMLToTextConverter();

        self::assertSame('', $converter->toPlainText($html));
    }
}
