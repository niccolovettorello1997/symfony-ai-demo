<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Service\ClassificationService;
use App\Utils\AgentCaller;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\AI\Platform\Result\ResultInterface;

class ClassificationServiceTest extends TestCase
{
    /** @var AgentCaller&MockObject */
    private AgentCaller $caller;
    private ClassificationService $service;

    protected function setUp(): void
    {
        $this->caller = $this->createMock(AgentCaller::class);
        $this->service = new ClassificationService(
            'classify the text.',
            'fallback classification',
            $this->caller
        );
    }

    public function test_classify_with_labels_uses_normal_prompt(): void
    {
        $result = self::createStub(ResultInterface::class);

        $this->caller
            ->expects($this->once())
            ->method('callWithPrompt')
            ->with('classify the text. Labels: urgent, spam', 'email content')
            ->willReturn($result);

        $this->service->classify('email content', ['urgent', 'spam']);
    }

    public function test_classify_without_labels_uses_fallback_prompt(): void
    {
        $result = self::createStub(ResultInterface::class);

        $this->caller
            ->expects($this->once())
            ->method('callWithPrompt')
            ->with('fallback classification', 'email content')
            ->willReturn($result);

        $this->service->classify('email content');
    }
}
