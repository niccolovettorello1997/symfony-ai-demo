<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ClassificationRequest
{
    #[Assert\NotBlank]
    #[Assert\Length(max:500)]
    public string $text;

    #[Assert\Type('array')]
    #[Assert\All([
        new Assert\Type('string'),
        new Assert\NotBlank,
        new Assert\Length(max: 10)
    ])]
    public ?array $labels = null;
}