<?php

declare(strict_types=1);

namespace App\Http\Integrations\YandexGames\DTOs\GameDto;

use App\Http\Integrations\YandexGames\DTOs\FeedDto\WidgetDto\GameDto\DeveloperDto;
use App\Http\Integrations\YandexGames\DTOs\FeedDto\WidgetDto\GameDto\RatingDto;
use App\Http\Integrations\YandexGames\Values\AppId;
use App\Http\Integrations\YandexGames\Values\Url;
use Illuminate\Support\Carbon;

final readonly class GameDto
{
    /**
     * @param CategoryDto[] $categories
     */
    public function __construct(
        public DeveloperDto $developer,
        public array $categoryIds,
        public string $title,
        public AppId $id,
        public RatingDto $rating,
        public Url $url,
        public array $categoryNames,
        public string $description,
        public string $instruction,
        public string $seoDescription,
        public string|null $generatedTitle,
        public string $seoTitle,
        public array $features,
        public array $tagIds,
        public array|null $score,
        public float $minLoadTime,
        public Carbon $firstPublished,
        public array $extraFeatures,
        public array $badge,
        public array $categories,
    )
    {
    }
}
