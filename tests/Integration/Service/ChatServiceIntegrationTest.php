<?php

declare(strict_types=1);

namespace App\Tests\Integration\Service;

use App\Service\ChatService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ChatServiceIntegrationTest extends KernelTestCase
{
    private ChatService $chatService;

    protected function setUp(): void
    {
        if (!getenv('INTEGRATION_TESTS')) {
            self::markTestSkipped('Integration tests disabled. Set INTEGRATION_TESTS=1 to enable.');
        }

        self::bootKernel();

        $this->chatService = self::getContainer()->get(ChatService::class);
    }

    public function test_chat_returns_result(): void
    {
        $result = $this->chatService->replyTo('Hello AI');
        $content = $result->getContent();

        self::assertNotEmpty($content);
        self::assertIsString($content);
    }
}
