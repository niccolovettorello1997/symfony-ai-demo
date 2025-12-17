<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\AI\Agent\ChatAgent;
use App\Service\ChatService;
use App\Utils\HTMLToTextConverter;
use App\Utils\MarkdownToHtmlInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ChatServiceTest extends TestCase
{
    /** @var ChatAgent&MockObject */
    private ChatAgent $chatAgent;

    /** @var MarkdownToHtmlInterface&MockObject */
    private MarkdownToHtmlInterface $markdownToHtml;

    /** @var HTMLToTextConverter&MockObject */
    private HTMLToTextConverter $htmlToTextConverter;

    private ChatService $service;

    protected function setUp(): void
    {
        $this->chatAgent = $this->createMock(ChatAgent::class);
        $this->markdownToHtml = $this->createMock(MarkdownToHtmlInterface::class);
        $this->htmlToTextConverter = $this->createMock(HTMLToTextConverter::class);

        $this->service = new ChatService(
            $this->chatAgent,
            $this->markdownToHtml,
            $this->htmlToTextConverter
        );
    }

    public function test_reply_to_html(): void
    {
        $this->chatAgent
            ->expects($this->once())
            ->method('chat')
            ->with('hello')
            ->willReturn('**hello**');

        $this->markdownToHtml
            ->expects($this->once())
            ->method('convert')
            ->with('**hello**')
            ->willReturn('<strong>hello</strong>');

        $this->htmlToTextConverter
            ->expects($this->never())
            ->method('toPlainText');

        $result = $this->service->replyToHtml('hello');

        self::assertSame('<strong>hello</strong>', $result);
    }

    public function test_reply_to_plain_text(): void
    {
        $this->chatAgent
            ->expects($this->once())
            ->method('chat')
            ->with('hello')
            ->willReturn('**hello**');

        $this->markdownToHtml
            ->expects($this->once())
            ->method('convert')
            ->with('**hello**')
            ->willReturn('<strong>hello</strong>');

        $this->htmlToTextConverter
            ->expects($this->once())
            ->method('toPlainText')
            ->with('<strong>hello</strong>')
            ->willReturn('hello');

        $result = $this->service->replyToPlainText('hello');

        self::assertSame('hello', $result);
    }
}
