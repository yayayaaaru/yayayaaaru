<?php

declare(strict_types=1);

namespace App\Http\Integrations\YandexGames\Responses;

use App\Http\Integrations\YandexGames\DTOs\FeedDto\DeveloperDto as FeedDeveloperDto;
use App\Http\Integrations\YandexGames\DTOs\FeedDto\FeedGamesByDeveloperDto;
use App\Http\Integrations\YandexGames\DTOs\FeedDto\ItemDto\DeveloperDto as ItemDeveloperDto;
use App\Http\Integrations\YandexGames\DTOs\FeedDto\ItemDto\ItemDto;
use App\Http\Integrations\YandexGames\DTOs\FeedDto\WidgetDto\GameDto\BadgeDto;
use App\Http\Integrations\YandexGames\DTOs\FeedDto\WidgetDto\GameDto\FeaturesDto;
use App\Http\Integrations\YandexGames\DTOs\FeedDto\WidgetDto\GameDto\GridPositionDto;
use App\Http\Integrations\YandexGames\DTOs\FeedDto\WidgetDto\GameDto\RatingDto;
use App\Http\Integrations\YandexGames\DTOs\PageInfoDto;
use App\Http\Integrations\YandexGames\Enums\FeedType;
use App\Http\Integrations\YandexGames\Values\AgeRating;
use App\Http\Integrations\YandexGames\Values\AppId;
use App\Http\Integrations\YandexGames\Values\AppSlug;
use App\Http\Integrations\YandexGames\Values\Url;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use Saloon\Http\Response;

final readonly class GamesByDeveloperResponse implements Arrayable
{
    /**
     * @param FeedGamesByDeveloperDto[] $feed
     */
    public function __construct(
        public array $feed,
        public PageInfoDto $pageInfo,
        public FeedDeveloperDto $developer,
        public int $totalGamesCount,
        public int $gamesWithPromos,
        public string $gamesRequestId,
    )
    {
    }

    public static function fromSaloonResponse(Response $response): self
    {
        $json = $response->json();

        /** @var array $feed */
        $feed = $json['feed'];
        /** @var array $pageInfo */
        $pageInfo = $json['pageInfo'];
        /** @var array $developer */
        $developer = $json['developer'];
        /** @var int $totalGamesCount */
        $totalGamesCount = $json['totalGamesCount'];
        /** @var int $gamesWithPromos */
        $gamesWithPromos = $json['gamesWithPromos'];
        /** @var string $gamesRequestId */
        $gamesRequestId = $json['gamesRequestId'];

        $res = collect();

        collect($feed)->each(
            static fn(array $feedItem): Collection => $res->add(
                new FeedGamesByDeveloperDto(
                    FeedType::fromYandex($feedItem),
                    collect($feedItem['items'])->map(static fn(array $item) => new ItemDto(
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
                    ))->toArray(),
                    $feedItem['isFromFirstPage'],
                    $feedItem['requestId'],
                ),
            )
        );

        return new self(
            $res->toArray(),
            new PageInfoDto(
                $pageInfo['nextPageId'],
                $pageInfo['rtxReqId'],
                $pageInfo['isFirstPage'],
                $pageInfo['hasNextPage'],
            ),
            new FeedDeveloperDto(
                $developer['id'],
                $developer['name'],
                new Url($developer['shareImage']),
            ),
            $totalGamesCount,
            $gamesWithPromos,
            $gamesRequestId,
        );
    }

    public function toArray()
    {
        // TODO: Implement toArray() method.
    }
}
