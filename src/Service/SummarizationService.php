<?php

declare(strict_types=1);

namespace App\Service;

use App\Utils\AgentCaller;
use Symfony\AI\Platform\Result\ResultInterface;

class SummarizationService
{
    public function __construct(
        private readonly string $summarizationSystemPrompt,
        private readonly AgentCaller $agentCaller
    ) {
    }

    /**
     * Sends a text to summarize to the agent.
     * Set the agent to behave like an optimal summarization agent.
     *
     * @param  string $text
     * @return ResultInterface
     */
    public function summarize(string $text): ResultInterface
    {
        return $this->agentCaller
            ->callWithPrompt(
                $this->summarizationSystemPrompt,
                $text
            );
    }
}
