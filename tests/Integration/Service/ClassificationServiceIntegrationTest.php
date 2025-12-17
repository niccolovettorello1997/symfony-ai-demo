<?php

declare(strict_types=1);

namespace App\Tests\Integration\Service;

use App\Service\ClassificationService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ClassificationServiceIntegrationTest extends KernelTestCase
{
    private ClassificationService $classificationService;

    protected function setUp(): void
    {
        if (!getenv('INTEGRATION_TESTS')) {
            self::markTestSkipped('Integration tests disabled. Set INTEGRATION_TESTS=1 to enable.');
        }

        self::bootKernel();

        $this->classificationService = self::getContainer()->get(ClassificationService::class);
    }

    public function test_classification_returns_result_html(): void
    {
        $labels = ['Positive', 'Negative', 'Neutral'];

        $result = $this->classificationService->classifyHtml('Love Symfony AI!', $labels);

        $found = array_filter(
            $labels,
            fn ($label) => str_contains($result, $label)
        );

        self::assertNotEmpty($result);
        self::assertNotEmpty($found);
    }

    public function test_classification_returns_result_plain_text(): void
    {
        $labels = ['Positive', 'Negative', 'Neutral'];

        $result = $this->classificationService->classifyPlainText('Love Symfony AI!', $labels);

        $found = array_filter(
            $labels,
            fn ($label) => str_contains($result, $label)
        );

        self::assertNotEmpty($result);
        self::assertNotEmpty($found);
    }
}
