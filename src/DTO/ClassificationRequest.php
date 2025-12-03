<?php

declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

final class ClassificationRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(max:500)]
        public readonly string $text,
        /** @var null|array<string> $labels */
        #[Assert\Type('array')]
        #[Assert\All([
            new Assert\Type('string'),
            new Assert\NotBlank(),
            new Assert\Length(max: 10)
        ])]
        public readonly ?array $labels = null
    ) {
    }
}
