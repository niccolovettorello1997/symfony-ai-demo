<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class SummarizationRequest
{
    #[Assert\NotBlank]
    #[Assert\Length(max:500)]
    public string $text;
}