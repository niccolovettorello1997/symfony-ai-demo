<?php

declare(strict_types=1);

namespace App\Service;

use App\Utils\AgentCaller;
use Symfony\AI\Platform\Result\ResultInterface;

class ChatService
{
    public function __construct(
        private readonly string $chatSystemPrompt,
        private readonly AgentCaller $agentCaller
    ) {
    }

    /**
     * Sends a chat message to the agent.
     * Sets the agent to behave like an optimal chat agent.
     *
     * @param  string $message
     * @return ResultInterface
     */
    public function replyTo(string $message): ResultInterface
    {
        return $this->agentCaller
            ->callWithPrompt(
                $this->chatSystemPrompt,
                $message
            );
    }
}
