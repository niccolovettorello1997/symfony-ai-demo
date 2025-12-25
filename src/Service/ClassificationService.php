<?php

declare(strict_types=1);

namespace App\Service;

use App\AI\Agent\ClassificationAgent;
use App\Utils\HTMLToTextConverter;
use App\Utils\MarkdownToHtmlInterface;

class ClassificationService
{
    public function __construct(
        private readonly ClassificationAgent $classificationAgent,
        private readonly MarkdownToHtmlInterface $markdownToHtml,
        private readonly HTMLToTextConverter $htmlToTextConverter,
    ) {
    }

    /**
     * Generates sub-prompt for labels.
     *
     * @param  array<string>|null $labels
     * @return string|null
     */
    private function getCustomLabelsMessage(?array $labels): ?string
    {
        // Generate the sub-prompt for labels if provided
        $customLabelsMessage = null;

        if (null !== $labels) {
            $customLabelsMessage = sprintf(
                'User-defined labels: %s',
                implode(', ', $labels)
            );
        }

        return $customLabelsMessage;
    }

    /**
     * Sends a text with optional user-defined labels to the classification agent
     * and returns the rensponse in html.
     *
     * @param  string $text
     * @param  array<string>|null $labels
     * @return string
     */
    public function classifyHtml(string $text, ?array $labels = null): string
    {
        $customLabelsMessage = $this->getCustomLabelsMessage($labels);

        $reply = $this->classificationAgent
            ->classify(
                $text,
                $customLabelsMessage
            );

        return $this->markdownToHtml->convert($reply);
    }

    /**
     * Sends a text with optional user-defined labels to the classification agent
     * and returns the response in plain text.
     *
     * @param  string $text
     * @param  array<string>|null $labels
     * @return string
     */
    public function classifyPlainText(string $text, ?array $labels = null): string
    {
        $customLabelsMessage = $this->getCustomLabelsMessage($labels);

        $reply = $this->classificationAgent
            ->classify(
                $text,
                $customLabelsMessage
            );

        // Markdown -> HTML -> Text
        $htmlContent = $this->markdownToHtml->convert($reply);
        return $this->htmlToTextConverter->toPlainText($htmlContent);
    }
}
