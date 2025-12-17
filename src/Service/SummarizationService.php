<?php

declare(strict_types=1);

namespace App\Service;

use App\AI\Agent\SummarizationAgent;
use App\Utils\HTMLToTextConverter;
use App\Utils\MarkdownToHtmlInterface;

class SummarizationService
{
    public function __construct(
        private readonly SummarizationAgent $summarizationAgent,
        private readonly MarkdownToHtmlInterface $markdownToHtml,
        private readonly HTMLToTextConverter $htmlToTextConverter,
    ) {
    }

    /**
     * Sends a text to the summarization agent and gets the html response.
     *
     * @param  string $text
     * @return string
     */
    public function summarizeHtml(string $text): string
    {
        $reply = $this->summarizationAgent->summarize($text);

        return $this->markdownToHtml->convert($reply);
    }

    /**
     * Sends a text to the summarization agent and gets the plain text response.
     *
     * @param  string $text
     * @return string
     */
    public function summarizePlainText(string $text): string
    {
        $reply = $this->summarizationAgent->summarize($text);

        // Markdown -> HTML -> Text
        $htmlContent = $this->markdownToHtml->convert($reply);
        return $this->htmlToTextConverter->toPlainText($htmlContent);
    }
}
