<?php

declare(strict_types=1);

namespace App\DTOs;

final readonly class SourceDto
{
    public function __construct(
        public string $name,
        public string $external_id,
    )
    {
    }
}
