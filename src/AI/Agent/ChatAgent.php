<?php

declare(strict_types=1);

namespace App\AI\Agent;

use Symfony\AI\Agent\AgentInterface;
use Symfony\AI\Platform\Message\Message;
use Symfony\AI\Platform\Message\MessageBag;
use Symfony\Component\DependencyInjection\Attribute\Target;

class ChatAgent
{
    public function __construct(
        #[Target('ai.agent.chat')]
        private readonly AgentInterface $agent,
    ) {
    }

    /**
     * Sends the provided user message to the chat AI agent.
     *
     * @param  string $userMessage
     * @return string
     */
    public function chat(string $userMessage): string
    {
        $messages = new MessageBag(
            Message::ofUser($userMessage)
        );

        return $this->agent->call($messages)->getContent();
    }
}
