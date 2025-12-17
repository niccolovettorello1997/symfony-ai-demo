<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\AIResponse;
use App\DTO\ClassificationRequest;
use App\Service\ClassificationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class ClassificationController extends AbstractController
{
    public function __construct(
        private readonly ClassificationService $classificationService,
    ) {
    }

    #[Route(path: '/classify', name: 'classification_classify', methods: ['POST'])]
    public function classify(
        #[MapRequestPayload] ClassificationRequest $classification
    ): JsonResponse {
        $reply = $this->classificationService
            ->classifyPlainText(
                $classification->text,
                $classification->labels
            );

        return $this->json(new AIResponse($reply));
    }

    #[Route(path: '/classification', name: 'classification_ui', methods: ['GET', 'POST'])]
    public function classificationUI(Request $request): Response
    {
        $reply = null;

        if ($request->isMethod('POST')) {
            $text = $request->request->get('text', '');
            $labels = $request->request->get('labels', null);

            // Normalize labels and explode them into an array
            if (is_string($labels)) {
                $labels = array_filter(
                    array_map(
                        'trim',
                        explode(
                            ',',
                            $labels
                        )
                    ),
                    fn ($label) => $label !== ''
                );
            }

            if ($text !== '') {
                $reply = $this->classificationService
                    ->classifyHtml(
                        $text,
                        ($labels !== []) ? $labels : null
                    );
            }
        }

        return $this->render('classification/index.html.twig', [
            'reply' => $reply,
        ]);
    }
}
