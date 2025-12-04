<?php

namespace App\Controller;

use Symfony\AI\Agent\AgentInterface;
use Symfony\AI\Platform\Message\Message;
use Symfony\AI\Platform\Message\MessageBag;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AIDemoController extends AbstractController
{
    public function __construct(
        private readonly AgentInterface $agent,
    ) {
    }

    #[Route(path: '/ai-demo', name: 'ai-demo', methods: ['GET'])]
    public function aiDemo(Request $request): JsonResponse
    {
        $userMessage = $request->query->getString('message', 'Hello, AI!');

        $messages = new MessageBag(
            Message::forSystem('You are a helpful assistant.'),
            Message::ofUser($userMessage),
        );

        $result = $this->agent->call($messages);

        return $this->json([
            'user_message' => $userMessage,
            'ai_response' => $result->getContent(),
        ]);
    }
}
