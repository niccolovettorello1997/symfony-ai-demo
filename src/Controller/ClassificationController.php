<?php

namespace App\Controller;

use App\DTO\ClassificationRequest;
use App\DTO\Response;
use App\Service\AIService;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClassificationController extends AbstractController
{
    public function __construct(
        private readonly AIService $aiService
    ) {
    }

    #[Route(path: '/classification/classify', name: 'classification_classify', methods: ['POST'])]
    public function chat(
        #[MapRequestPayload] ClassificationRequest $classification
    ): JsonResponse {
        $result = $this->aiService
            ->classify(
                text: $classification->text,
                labels: $classification->labels
            );

        return new JsonResponse(
            data: new Response(
                reply: $result->getContent()
            )
        );
    }
}
