<?php

declare(strict_types=1);

namespace App\AI\Agent;

use Symfony\AI\Agent\AgentInterface;
use Symfony\AI\Platform\Message\Message;
use Symfony\AI\Platform\Message\MessageBag;

class ClassificationAgent
{
    public function __construct(
        private readonly string $systemPrompt,
        private readonly AgentInterface $agent,
    ) {
    }

    /**
     * Sends the provided user text with optional custom labels
     * to the classification AI agent.
     *
     * @param  string $userText
     * @param  string|null $labelsSubPrompt
     * @return string
     */
    public function classify(string $userText, ?string $labelsSubPrompt = null): string
    {
        $systemPrompt = $this->systemPrompt;

        // Append custom labels sub-prompt if provided
        if (null !== $labelsSubPrompt) {
            $systemPrompt .= ' ' . $labelsSubPrompt;
        }

        $messages = new MessageBag(
            Message::forSystem($systemPrompt),
            Message::ofUser($userText)
        );

        return $this->agent->call($messages)->getContent();
    }
}
