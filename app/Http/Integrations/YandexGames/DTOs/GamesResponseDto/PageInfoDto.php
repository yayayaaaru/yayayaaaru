<?php

declare(strict_types=1);

namespace App\Http\Integrations\YandexGames\DTOs\GamesResponseDto;

final readonly class PageInfoDto
{
    public function __construct(
        public ?string $nextPageId,
        public string $requestId,
        public bool $isFirstPage,
        public bool $hasNextPage,
    )
    {
    }
}
