<?php

declare(strict_types=1);

namespace App\Http\Integrations\YandexGames\DTOs\FeedDto;

use App\Http\Integrations\YandexGames\Contracts\FeedDto;
use App\Http\Integrations\YandexGames\Enums\FeedType;

final readonly class FeedAdvDto implements FeedDto
{
    public function __construct(
        public FeedType $type,
        public array $items,
        public int $pageNumber,
        public bool $isFromFirstPage,
        public string $requestId,
    )
    {
    }
}
