<?php

declare(strict_types=1);

namespace App\Http\Integrations\YandexGames\DTOs\GameDto;

final readonly class CategoryDto
{
    public function __construct(
        public int $id,
        public string $name,
    )
    {
    }
}
