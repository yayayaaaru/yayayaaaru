<?php

declare(strict_types=1);

namespace App\Http\Integrations\YandexGames\DTOs\FeedDto;

use App\Http\Integrations\YandexGames\DTOs\FeedDto\ItemDto\ItemDto;
use App\Http\Integrations\YandexGames\Enums\FeedType;

final readonly class FeedGamesByDeveloperDto
{
    /**
     * @param ItemDto[] $items
     */
    public function __construct(
        public FeedType $type,
        public array $items,
        public bool $isFromFirstPage,
        public string $requestId
    )
    {
    }
}
