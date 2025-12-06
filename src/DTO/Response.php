<?php

namespace App\DTO;

class Response
{
    public function __construct(
        public readonly string $reply,
    ) {
    }
}
