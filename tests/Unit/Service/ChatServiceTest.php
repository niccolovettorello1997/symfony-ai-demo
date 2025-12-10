<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Service\ChatService;
use App\Utils\AgentCaller;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\AI\Platform\Result\ResultInterface;

class ChatServiceTest extends TestCase
{
    /**
     * @var AgentCaller&MockObject
     */
    private AgentCaller $caller;
    private ChatService $service;

    protected function setUp(): void
    {
        $this->caller = $this->createMock(AgentCaller::class);
        $this->service = new ChatService(
            'chat system prompt',
            $this->caller
        );
    }

    public function test_chat_delegates_to_agent_caller(): void
    {
        $result = self::createStub(ResultInterface::class);

        $this->caller
            ->expects($this->once())
            ->method('callWithPrompt')
            ->with('chat system prompt', 'hello ai')
            ->willReturn($result);

        $this->service->replyTo('hello ai');
    }
}
