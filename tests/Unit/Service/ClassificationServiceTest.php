<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\AI\Agent\ClassificationAgent;
use App\Service\ClassificationService;
use App\Utils\HTMLToTextConverter;
use App\Utils\MarkdownToHtmlInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ClassificationServiceTest extends TestCase
{
    /** @var ClassificationAgent&MockObject */
    private ClassificationAgent $classificationAgent;

    /** @var MarkdownToHtmlInterface&MockObject */
    private MarkdownToHtmlInterface $markdownToHtml;

    /** @var HTMLToTextConverter&MockObject */
    private HTMLToTextConverter $htmlToTextConverter;

    private ClassificationService $service;

    protected function setUp(): void
    {
        $this->classificationAgent = $this->createMock(ClassificationAgent::class);
        $this->markdownToHtml = $this->createMock(MarkdownToHtmlInterface::class);
        $this->htmlToTextConverter = $this->createMock(HTMLToTextConverter::class);

        $this->service = new ClassificationService(
            $this->classificationAgent,
            $this->markdownToHtml,
            $this->htmlToTextConverter
        );
    }

    public function test_classify_html(): void
    {
        $this->classificationAgent
            ->expects($this->once())
            ->method('classify')
            ->with('Text to classify')
            ->willReturn('**Business**');

        $this->markdownToHtml
            ->expects($this->once())
            ->method('convert')
            ->with('**Business**')
            ->willReturn('<strong>Business</strong>');

        $this->htmlToTextConverter
            ->expects($this->never())
            ->method('toPlainText');

        $result = $this->service->classifyHtml('Text to classify');

        self::assertSame('<strong>Business</strong>', $result);
    }

    public function test_classify_plain_text(): void
    {
        $this->classificationAgent
            ->expects($this->once())
            ->method('classify')
            ->with('Text to classify')
            ->willReturn('**Business**');

        $this->markdownToHtml
            ->expects($this->once())
            ->method('convert')
            ->with('**Business**')
            ->willReturn('<strong>Business</strong>');

        $this->htmlToTextConverter
            ->expects($this->once())
            ->method('toPlainText')
            ->with('<strong>Business</strong>')
            ->willReturn('Business');

        $result = $this->service->classifyPlainText('Text to classify');

        self::assertSame('Business', $result);
    }
}
