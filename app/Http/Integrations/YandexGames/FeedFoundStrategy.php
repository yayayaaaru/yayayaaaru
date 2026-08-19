<?php

declare(strict_types=1);

namespace App\Http\Integrations\YandexGames;

use App\Http\Integrations\YandexGames\Contracts\FeedStrategy;
use App\Http\Integrations\YandexGames\DTOs\FeedDto\FeedGamesByDeveloperDto;
use App\Http\Integrations\YandexGames\DTOs\FeedDto\ItemDto\CategoryDto;
use App\Http\Integrations\YandexGames\DTOs\FeedDto\ItemDto\DeveloperDto as ItemDeveloperDto;
use App\Http\Integrations\YandexGames\DTOs\FeedDto\ItemDto\ItemDto;
use App\Http\Integrations\YandexGames\DTOs\FeedDto\WidgetDto\GameDto\FeaturesDto;
use App\Http\Integrations\YandexGames\DTOs\FeedDto\WidgetDto\GameDto\GridPositionDto;
use App\Http\Integrations\YandexGames\DTOs\FeedDto\WidgetDto\GameDto\RatingDto;
use App\Http\Integrations\YandexGames\Enums\FeedType;
use App\Http\Integrations\YandexGames\Values\AgeRating;
use App\Http\Integrations\YandexGames\Values\AppId;
use App\Http\Integrations\YandexGames\Values\Url;

final readonly class FeedFoundStrategy implements FeedStrategy
{
    public function dto(array $context): FeedGamesByDeveloperDto
    {
        return new FeedGamesByDeveloperDto(
            FeedType::fromYandex($context),
            collect($context['items'])->map(static fn(array $item) => new ItemDto(
                new ItemDeveloperDto(
                    $item['developer']['id'],
                    $item['developer']['name'],
                    $url = ($logo = $item['developer']['logoURL'] ?? null) ? new Url($logo) : null,
                ),
                $item['categoryIDs'],
                $item['title'],
                new AppId($item['appID']),
                new RatingDto(
                    $item['ratingCount'],
                    $item['rating'],
                ),
                $item['tagIDs'],
                $item['categoryNames'],
                new FeaturesDto(
                    new AgeRating($item['features']['age_rating']),
                    $item['features']['user_data_required'],
                ),
                $item['type'],
                new GridPositionDto(
                    $item['column'],
                    $item['row'],
                ),
                $item['requestId'],
                array_map(
                    static fn(int $id, string $title): CategoryDto => new CategoryDto($id, $title),
                    $item['categoryIDs'],
                    $item['categoryNames']
                ),
            ))->toArray(),
            $context['isFromFirstPage'],
            $context['requestId'],
        );
    }
}
