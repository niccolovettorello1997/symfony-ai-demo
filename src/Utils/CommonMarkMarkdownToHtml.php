<?php

declare(strict_types=1);

namespace App\Utils;

use League\CommonMark\CommonMarkConverter;

final class CommonMarkMarkdownToHtml implements MarkdownToHtmlInterface
{
    public function __construct(
        private readonly CommonMarkConverter $commonMarkConverter,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function convert(string $markdown): string
    {
        return $this->commonMarkConverter->convert($markdown)->getContent();
    }
}
