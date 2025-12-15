<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\AI\Agent\SummarizationAgent;
use App\Service\SummarizationService;
use App\Utils\HTMLToTextConverter;
use App\Utils\MarkdownToHtmlInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SummarizationServiceTest extends TestCase
{
    /** @var SummarizationAgent&MockObject */
    private SummarizationAgent $summarizationAgent;

    /** @var MarkdownToHtmlInterface&MockObject */
    private MarkdownToHtmlInterface $markdownToHtml;

    /** @var HTMLToTextConverter&MockObject */
    private HTMLToTextConverter $htmlToTextConverter;

    private SummarizationService $service;

    protected function setUp(): void
    {
        $this->summarizationAgent = $this->createMock(SummarizationAgent::class);
        $this->markdownToHtml = $this->createMock(MarkdownToHtmlInterface::class);
        $this->htmlToTextConverter = $this->createMock(HTMLToTextConverter::class);

        $this->service = new SummarizationService(
            $this->summarizationAgent,
            $this->markdownToHtml,
            $this->htmlToTextConverter
        );
    }

    public function test_summarize_html(): void
    {
        $this->summarizationAgent
            ->expects($this->once())
            ->method('summarize')
            ->with('Text to summarize')
            ->willReturn('**Summarization**');

        $this->markdownToHtml
            ->expects($this->once())
            ->method('convert')
            ->with('**Summarization**')
            ->willReturn('<strong>Summarization</strong>');

        $this->htmlToTextConverter
            ->expects($this->never())
            ->method('toPlainText');

        $result = $this->service->summarizeHtml('Text to summarize');

        self::assertSame('<strong>Summarization</strong>', $result);
    }

    public function test_summarize_plain_text(): void
    {
        $this->summarizationAgent
            ->expects($this->once())
            ->method('summarize')
            ->with('Text to summarize')
            ->willReturn('**Summarization**');

        $this->markdownToHtml
            ->expects($this->once())
            ->method('convert')
            ->with('**Summarization**')
            ->willReturn('<strong>Summarization</strong>');

        $this->htmlToTextConverter
            ->expects($this->once())
            ->method('toPlainText')
            ->with('<strong>Summarization</strong>')
            ->willReturn('Summarization');

        $result = $this->service->summarizePlainText('Text to summarize');

        self::assertSame('Summarization', $result);
    }
}
