<?php

declare(strict_types=1);

namespace App\Tests\Unit\AI\Agent;

use App\AI\Agent\ClassificationAgent;
use PHPUnit\Framework\TestCase;
use Symfony\AI\Agent\AgentInterface;
use Symfony\AI\Platform\Message\Content\Text;
use Symfony\AI\Platform\Message\MessageBag;
use Symfony\AI\Platform\Result\ResultInterface;

class ClassificationAgentTest extends TestCase
{
    public function test_classify_builds_messages_and_returns_response(): void
    {
        $userText  = 'Hello Symfony AI!';
        $labels = 'User defined labels: positive, negative, neutral';

        $result = $this->createMock(ResultInterface::class);
        $result
            ->expects($this->once())
            ->method('getContent')
            ->willReturn('Positive');

        $agent = $this->createMock(AgentInterface::class);
        $agent
            ->expects($this->once())
            ->method('call')
            ->with(self::callback(function (MessageBag $bag) use ($userText, $labels): bool {
                $messages = iterator_to_array($bag);

                self::assertCount(2, $messages);

                /** @var Text $userMessageContent */
                $userMessageContent = $messages[0]->getContent()[0];

                self::assertSame(
                    $userText,
                    $userMessageContent->getText()
                );

                /** @var Text $userCustomLabelsMessage */
                $userCustomLabelsMessage = $messages[1]->getContent()[0];

                self::assertSame(
                    $labels,
                    $userCustomLabelsMessage->getText()
                );

                return true;
            }))
            ->willReturn($result);

        $classificationAgent = new ClassificationAgent($agent);

        $response = $classificationAgent->classify($userText, $labels);

        self::assertSame('Positive', $response);
    }
}
