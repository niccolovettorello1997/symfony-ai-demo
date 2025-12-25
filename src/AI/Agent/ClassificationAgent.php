<?php

declare(strict_types=1);

namespace App\AI\Agent;

use Symfony\AI\Agent\AgentInterface;
use Symfony\AI\Platform\Message\Message;
use Symfony\AI\Platform\Message\MessageBag;
use Symfony\Component\DependencyInjection\Attribute\Target;

class ClassificationAgent
{
    public function __construct(
        #[Target('ai.agent.classification')]
        private readonly AgentInterface $agent,
    ) {
    }

    /**
     * Sends the provided user text with optional custom labels
     * to the classification AI agent.
     *
     * @param  string $userText
     * @param  string|null $customLabelsMessage
     * @return string
     */
    public function classify(string $userText, ?string $customLabelsMessage = null): string
    {
        $messages = new MessageBag(
            Message::ofUser($userText)
        );

        if (null !== $customLabelsMessage) {
            $messages->add(Message::ofUser($customLabelsMessage));
        }

        return $this->agent->call($messages)->getContent();
    }
}
