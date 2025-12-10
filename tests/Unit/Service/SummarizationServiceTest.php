<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Service\SummarizationService;
use App\Utils\AgentCaller;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\AI\Platform\Result\ResultInterface;

class SummarizationServiceTest extends TestCase
{
    /** @var AgentCaller&MockObject */
    private AgentCaller $caller;
    private SummarizationService $service;

    protected function setUp(): void
    {
        $this->caller = $this->createMock(AgentCaller::class);
        $this->service = new SummarizationService(
            'summarization prompt',
            $this->caller
        );
    }

    public function test_summarize_delegates_to_agent_caller(): void
    {
        $result = self::createStub(ResultInterface::class);

        $this->caller
            ->expects($this->once())
            ->method('callWithPrompt')
            ->with('summarization prompt', 'long text')
            ->willReturn($result);

        $this->service->summarize('long text');
    }
}
