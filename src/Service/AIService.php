<?php

namespace App\Service;

use Symfony\AI\Agent\AgentInterface;
use Symfony\AI\Platform\Message\Message;
use Symfony\AI\Platform\Message\MessageBag;
use Symfony\AI\Platform\Result\ResultInterface;

class AIService
{
    public function __construct(
        private readonly string $chatSystemPrompt,
        private readonly string $summarizationSystemPrompt,
        private readonly string $classificationSystemPrompt,
        private readonly string $classificationFallbackSystemPrompt,
        private readonly AgentInterface $agent
    ) {
    }

    /**
     * Sends a chat message to the agent.
     * Set the agent to behave like an optimal chat agent.
     * 
     * @param  string $message
     * @return ResultInterface
     */
    public function chat(string $message): ResultInterface
    {
        $messages = new MessageBag(
            Message::forSystem($this->chatSystemPrompt),
            Message::ofUser($message)
        );

        return $this->agent->call(messages: $messages);
    }

    /**
     * Sends a text to the agent.
     * Set the agent to behave like an optimal summarization agent.
     * 
     * @param  string $text
     * @return ResultInterface
     */
    public function summarize(string $text): ResultInterface
    {
        $messages = new MessageBag(
            Message::forSystem($this->summarizationSystemPrompt),
            Message::ofUser($text)
        );

        return $this->agent->call(messages: $messages);
    }

    /**
     * Sends a text to the agent.
     * Set the agent to behave like an optimal classification agent.
     * If no set of labels is provided within the user message,
     * uses a fallback prompt to instruct the agent accordingly.
     * 
     * @param  string $text
     * @param  array<string>|null $labels
     * @return ResultInterface
     */
    public function classify(string $text, ?array $labels = null): ResultInterface
    {
        $systemPrompt = $this->classificationFallbackSystemPrompt;

        // Append the list of custom labels to the system prompt if it's provided
        if (null !== $labels) {
            $systemPrompt = $this->classificationSystemPrompt . ' ' . \implode(', ', $labels);
        }

        $messages = new MessageBag(
            Message::forSystem($systemPrompt),
            Message::ofUser($text)
        );

        return $this->agent->call(messages: $messages);
    }
}
