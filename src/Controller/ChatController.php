<?php

namespace App\Controller;

use App\DTO\Response;
use App\Service\AIService;
use App\DTO\ChatNewMessageRequest;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ChatController extends AbstractController
{
    public function __construct(
        private readonly AIService $aiService
    ) {
    }

    #[Route(path: '/chat/new-message', name: 'chat_new_message', methods: ['POST'])]
    public function chat(
        #[MapRequestPayload] ChatNewMessageRequest $chatMessage
    ): JsonResponse {
        $result = $this->aiService
            ->chat(message: $chatMessage->message);

        return new JsonResponse(
            data: new Response(
                reply: $result->getContent()
            )
        );
    }
}
