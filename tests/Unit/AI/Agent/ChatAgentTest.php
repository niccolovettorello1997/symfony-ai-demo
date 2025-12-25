<?php

declare(strict_types=1);

namespace App\Tests\Unit\AI\Agent;

use App\AI\Agent\ChatAgent;
use PHPUnit\Framework\TestCase;
use Symfony\AI\Agent\AgentInterface;
use Symfony\AI\Platform\Message\Content\Text;
use Symfony\AI\Platform\Message\MessageBag;
use Symfony\AI\Platform\Result\ResultInterface;

class ChatAgentTest extends TestCase
{
    public function test_chat_builds_messages_and_returns_response(): void
    {
        $userMessage  = 'Hello';

        $result = $this->createMock(ResultInterface::class);
        $result
            ->expects($this->once())
            ->method('getContent')
            ->willReturn('Hi there');

        $agent = $this->createMock(AgentInterface::class);
        $agent
            ->expects($this->once())
            ->method('call')
            ->with(self::callback(function (MessageBag $bag) use ($userMessage): bool {
                $messages = iterator_to_array($bag);

                self::assertCount(1, $messages);

                /** @var Text $userMessageContent */
                $userMessageContent = $messages[0]->getContent()[0];

                self::assertSame(
                    $userMessage,
                    $userMessageContent->getText()
                );

                return true;
            }))
            ->willReturn($result);

        $chatAgent = new ChatAgent($agent);

        $response = $chatAgent->chat($userMessage);

        self::assertSame('Hi there', $response);
    }
}
