<?php

declare(strict_types=1);

namespace App\DTOs;

use App\Enums\SourceName as Name;

final readonly class SourceDto
{
    public function __construct(
        public Name $name,
        public string $externalId,
    )
    {
    }
}
