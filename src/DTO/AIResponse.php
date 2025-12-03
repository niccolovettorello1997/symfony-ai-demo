<?php

declare(strict_types=1);

namespace App\DTO;

final class AIResponse
{
    public function __construct(
        public readonly string $reply,
    ) {
    }
}
