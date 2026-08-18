<?php

declare(strict_types=1);

namespace App\Http\Integrations\YandexGames\DTOs\FeedDto\WidgetDto\GameDto;

use App\Http\Integrations\YandexGames\DTOs\FeedDto\WidgetDto\GameDto\GameMediaDto\GameMediaDto;
use App\Http\Integrations\YandexGames\DTOs\GamesResponseDto\FeedDto\RequestId;
use App\Http\Integrations\YandexGames\Values\AppId;
use App\Http\Integrations\YandexGames\Values\AppSlug;

final readonly class GameDto
{
    /**
     * @param int[] $categoryIds
     * @param int[] $tagIds
     */
    public function __construct(
        public DeveloperDto $developer,
        public array $categoryIds,
        public string $title,
        public AppSlug $slug,
        public AppId $id,
        public RatingDto $rating,
        public GameMediaDto $media,
        public array $tagIds,
        public GameFeaturesDto $features,
        public ?BadgeDto $badge,
        public GridPositionDto $position,
        public string $requestId,
    )
    {
    }
}
