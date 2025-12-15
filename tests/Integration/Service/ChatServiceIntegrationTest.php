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

    public function test_chat_returns_result_html(): void
    {
        $result = $this->chatService->replyToHtml('Hello AI');

        self::assertNotEmpty($result);
    }

    public function test_chat_returns_result_plain_text(): void
    {
        $result = $this->chatService->replyToPlainText('Hello AI');

        self::assertNotEmpty($result);
    }
}
