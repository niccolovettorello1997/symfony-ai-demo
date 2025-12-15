<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\AIResponse;
use App\DTO\ChatMessageRequest;
use App\Service\ChatService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class ChatController extends AbstractController
{
    public function __construct(
        private readonly ChatService $chatService,
    ) {
    }

    #[Route(path: '/chat/new-message', name: 'chat_new_message', methods: ['POST'])]
    public function chat(
        #[MapRequestPayload] ChatMessageRequest $chatMessage
    ): JsonResponse {
        $reply = $this->chatService->replyToPlainText(message: $chatMessage->message);

        return $this->json(new AIResponse($reply));
    }

    #[Route(path: '/chat', name: 'chat_ui', methods: ['GET','POST'])]
    public function chatUI(Request $request): Response
    {
        $reply = null;

        if ($request->isMethod('POST')) {
            $message = $request->request->get('message', '');

            if ($message !== '') {
                $reply = $this->chatService->replyToHtml($message);
            }
        }

        return $this->render('chat/index.html.twig', [
            'reply' => $reply,
        ]);
    }
}
