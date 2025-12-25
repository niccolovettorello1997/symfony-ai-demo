<?php

declare(strict_types=1);

namespace App\Tests\Unit\AI\Agent;

use App\AI\Agent\SummarizationAgent;
use PHPUnit\Framework\TestCase;
use Symfony\AI\Agent\AgentInterface;
use Symfony\AI\Platform\Message\Content\Text;
use Symfony\AI\Platform\Message\MessageBag;
use Symfony\AI\Platform\Result\ResultInterface;

class SummarizationAgentTest extends TestCase
{
    public function test_summarize_builds_messages_and_returns_response(): void
    {
        $userText  = 'Text to summarize.';

        $result = $this->createMock(ResultInterface::class);
        $result
            ->expects($this->once())
            ->method('getContent')
            ->willReturn('Summary of the text.');

        $agent = $this->createMock(AgentInterface::class);
        $agent
            ->expects($this->once())
            ->method('call')
            ->with(self::callback(function (MessageBag $bag) use ($userText): bool {
                $messages = iterator_to_array($bag);

                self::assertCount(1, $messages);

                /** @var Text $userMessageContent */
                $userMessageContent = $messages[0]->getContent()[0];

                self::assertSame(
                    $userText,
                    $userMessageContent->getText()
                );

                return true;
            }))
            ->willReturn($result);

        $summarizationAgent = new SummarizationAgent($agent);

        $response = $summarizationAgent->summarize($userText);

        self::assertSame('Summary of the text.', $response);
    }
}
