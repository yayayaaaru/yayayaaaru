<?php

declare(strict_types=1);

namespace App\Http\Integrations\YandexGames\DTOs\FeedDto\ItemDto;

use App\Http\Integrations\YandexGames\DTOs\FeedDto\WidgetDto\GameDto\FeaturesDto;
use App\Http\Integrations\YandexGames\DTOs\FeedDto\WidgetDto\GameDto\GridPositionDto;
use App\Http\Integrations\YandexGames\DTOs\FeedDto\WidgetDto\GameDto\RatingDto;
use App\Http\Integrations\YandexGames\Values\AppId;

final readonly class ItemDto
{
    public function __construct(
        public DeveloperDto $developer,
        public array $categoryIds,
        public string $title,
        public AppId $appId,
        public RatingDto $rating,
        public array $tagIds,
        public array $categoryNames,
        public FeaturesDto $features,
        public string $type,
        public GridPositionDto $position,
        public string $requestId,
    )
    {
    }
}
