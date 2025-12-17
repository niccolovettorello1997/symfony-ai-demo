<?php

declare(strict_types=1);

namespace App\Utils;

interface MarkdownToHtmlInterface
{
    /**
     * Converts Markdown into HTML.
     *
     * @param  string $markdown
     * @return string
     */
    public function convert(string $markdown): string;
}
