<?php

declare(strict_types=1);

namespace App\Tests\Unit\Utils;

use App\Utils\AgentCaller;
use PHPUnit\Framework\TestCase;
use Symfony\AI\Agent\AgentInterface;
use Symfony\AI\Platform\Message\Content\Text;
use Symfony\AI\Platform\Message\MessageBag;
use Symfony\AI\Platform\Result\ResultInterface;

class AgentCallerTest extends TestCase
{
    public function test_builds_messagebag_and_calls_agent(): void
    {
        $agent = $this->createMock(AgentInterface::class);
        $caller = new AgentCaller($agent);

        $result = self::createStub(ResultInterface::class);

        $agent
            ->expects($this->once())
            ->method('call')
            ->with(self::callback(function (MessageBag $bag) {
                /** @var Text $text */
                $text = $bag->getUserMessage()->getContent()[0];

                self::assertStringContainsString('sys prompt', $bag->getSystemMessage()->getContent());
                self::assertStringContainsString('user text', $text->getText());
                return true;
            }))
            ->willReturn($result);

        $caller->callWithPrompt('sys prompt', 'user text');
    }
}
