<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\AIResponse;
use App\DTO\SummarizationRequest;
use App\Service\SummarizationService;
use App\Utils\HTMLToTextConverter;
use League\CommonMark\CommonMarkConverter;
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
        private readonly CommonMarkConverter $markdownConverter,
        private readonly HTMLToTextConverter $htmlToTextConverter,
    ) {
    }

    #[Route(path: '/summarize', name: 'summarization_summarize', methods: ['POST'])]
    public function summarize(
        #[MapRequestPayload] SummarizationRequest $summarization
    ): JsonResponse {
        $result = $this->summarizationService
            ->summarize(text: $summarization->text);

        // Markdown -> HTML -> Plain Text
        $htmlContent = $this->markdownConverter->convert($result->getContent())->getContent();
        $textContent = $this->htmlToTextConverter->toPlainText($htmlContent);

        return $this->json(
            data: new AIResponse(
                reply: $textContent
            )
        );
    }

    #[Route(path: '/summarization', name: 'summarization_ui', methods: ['GET','POST'])]
    public function summarizationUI(Request $request): Response
    {
        $reply = null;

        if ($request->isMethod('POST')) {
            $text = $request->request->get('text', '');

            if ($text !== '') {
                $result = $this->summarizationService->summarize($text);
                $reply = $result->getContent();
            }
        }

        return $this->render('summarization/index.html.twig', [
            'reply' => $this->markdownConverter->convert($reply ?? '')->getContent(),
        ]);
    }
}
