<?php

declare(strict_types=1);

namespace App\Utils;

use Symfony\AI\Agent\AgentInterface;
use Symfony\AI\Platform\Message\Message;
use Symfony\AI\Platform\Message\MessageBag;
use Symfony\AI\Platform\Result\ResultInterface;

class AgentCaller
{
    public function __construct(
        private readonly AgentInterface $agent
    ) {
    }

    /**
     * Calls the configured Ai Agent with a prompt for the system
     * and a user message.
     *
     * @param  string $systemPrompt
     * @param  string $userMessage
     * @return ResultInterface
     */
    public function callWithPrompt(string $systemPrompt, string $userMessage): ResultInterface
    {
        $messages = new MessageBag(
            Message::forSystem($systemPrompt),
            Message::ofUser($userMessage)
        );

        return $this->agent->call($messages);
    }
}
