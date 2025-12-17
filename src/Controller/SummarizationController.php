<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\AIResponse;
use App\DTO\SummarizationRequest;
use App\Service\SummarizationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class SummarizationController extends AbstractController
{
    public function __construct(
        private readonly SummarizationService $summarizationService,
    ) {
    }

    #[Route(path: '/summarize', name: 'summarization_summarize', methods: ['POST'])]
    public function summarize(
        #[MapRequestPayload] SummarizationRequest $summarization
    ): JsonResponse {
        $reply = $this->summarizationService->summarizePlainText(text: $summarization->text);

        return $this->json(new AIResponse($reply));
    }

    #[Route(path: '/summarization', name: 'summarization_ui', methods: ['GET','POST'])]
    public function summarizationUI(Request $request): Response
    {
        $reply = null;

        if ($request->isMethod('POST')) {
            $text = $request->request->get('text', '');

            if ($text !== '') {
                $reply = $this->summarizationService->summarizeHtml($text);
            }
        }

        return $this->render('summarization/index.html.twig', [
            'reply' => $reply,
        ]);
    }
}
