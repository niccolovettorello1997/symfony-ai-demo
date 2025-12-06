<?php

namespace App\Controller;

use App\DTO\Response;
use App\Service\AIService;
use App\DTO\SummarizationRequest;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SummarizationController extends AbstractController
{
    public function __construct(
        private readonly AIService $aiService
    ) {
    }

    #[Route(path: '/summarization/summarize', name: 'summarization_summarize', methods: ['POST'])]
    public function chat(
        #[MapRequestPayload] SummarizationRequest $summarization
    ): JsonResponse {
        $result = $this->aiService
            ->summarize(text: $summarization->text);

        return new JsonResponse(
            data: new Response(
                reply: $result->getContent()
            )
        );
    }
}
