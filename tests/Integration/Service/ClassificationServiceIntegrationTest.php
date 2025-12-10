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

    public function test_classification_returns_result(): void
    {
        $labels = ['Positive', 'Negative', 'Neutral'];

        $result = $this->classificationService->classify('Love Symfony AI!', $labels);
        $content = $result->getContent();

        $found = array_filter(
            $labels,
            fn ($label) => str_contains($content, $label)
        );

        self::assertNotEmpty($content);
        self::assertNotEmpty($found);
    }
}
