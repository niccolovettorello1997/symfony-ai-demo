<?php

declare(strict_types=1);

namespace App\Service;

use App\AI\Agent\ChatAgent;
use App\Utils\HTMLToTextConverter;
use App\Utils\MarkdownToHtmlInterface;

class ChatService
{
    public function __construct(
        private readonly ChatAgent $chatAgent,
        private readonly MarkdownToHtmlInterface $markdownToHtml,
        private readonly HTMLToTextConverter $htmlToTextConverter,
    ) {
    }

    /**
     * Sends a chat message to the chat agent and gets the html response.
     *
     * @param  string $message
     * @return string
     */
    public function replyToHtml(string $message): string
    {
        $reply = $this->chatAgent->chat($message);

        return $this->markdownToHtml->convert($reply);
    }

    /**
     * Sends a chat message to the chat agent and gets the plain text response.
     *
     * @param  string $message
     * @return string
     */
    public function replyToPlainText(string $message): string
    {
        $reply = $this->chatAgent->chat($message);

        // Markdown -> HTML -> Text
        $htmlContent = $this->markdownToHtml->convert($reply);
        return $this->htmlToTextConverter->toPlainText($htmlContent);
    }
}
