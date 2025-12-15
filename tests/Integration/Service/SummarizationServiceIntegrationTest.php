<?php

declare(strict_types=1);

namespace App\Tests\Integration\Service;

use App\Service\SummarizationService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SummarizationServiceIntegrationTest extends KernelTestCase
{
    private SummarizationService $summarizationService;

    protected function setUp(): void
    {
        if (!getenv('INTEGRATION_TESTS')) {
            self::markTestSkipped('Integration tests disabled. Set INTEGRATION_TESTS=1 to enable.');
        }

        self::bootKernel();

        $this->summarizationService = self::getContainer()->get(SummarizationService::class);
    }

    public function test_summarization_returns_result_html(): void
    {
        $result = $this->summarizationService->summarizeHtml('This is a long text to summarize.');

        self::assertNotEmpty($result);
    }

    public function test_summarization_returns_result_plain_text(): void
    {
        $result = $this->summarizationService->summarizePlainText('This is a long text to summarize.');

        self::assertNotEmpty($result);
    }
}
