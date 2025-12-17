<?php

declare(strict_types=1);

namespace App\AI\Agent;

use Symfony\AI\Agent\AgentInterface;
use Symfony\AI\Platform\Message\Message;
use Symfony\AI\Platform\Message\MessageBag;

class SummarizationAgent
{
    public function __construct(
        private readonly string $systemPrompt,
        private readonly AgentInterface $agent,
    ) {
    }

    /**
     * Sends the provided user text to the summarization AI agent.
     *
     * @param  string $userText
     * @return string
     */
    public function summarize(string $userText): string
    {
        $messages = new MessageBag(
            Message::forSystem($this->systemPrompt),
            Message::ofUser($userText)
        );

        return $this->agent->call($messages)->getContent();
    }
}
