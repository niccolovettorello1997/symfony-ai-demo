<?php

declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

final class SummarizationRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(max:500)]
        public readonly string $text
    ) {
    }
}
