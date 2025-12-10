<?php

declare(strict_types=1);

namespace App\Service;

use App\Utils\AgentCaller;
use Symfony\AI\Platform\Result\ResultInterface;

class ClassificationService
{
    public function __construct(
        private readonly string $classificationSystemPrompt,
        private readonly string $classificationFallbackSystemPrompt,
        private readonly AgentCaller $agentCaller
    ) {
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
            $systemPrompt = sprintf(
                '%s Labels: %s',
                $this->classificationSystemPrompt,
                implode(', ', $labels)
            );
        }

        return $this->agentCaller
            ->callWithPrompt(
                $systemPrompt,
                $text
            );
    }
}
