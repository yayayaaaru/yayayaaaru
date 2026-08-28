<?php

declare(strict_types=1);

namespace App\Http\Integrations\YandexGames\Responses;

use App\Http\Integrations\YandexGames\DTOs\FeedDto\DeveloperDto as FeedDeveloperDto;
use App\Http\Integrations\YandexGames\DTOs\FeedDto\FeedAdvDto;
use App\Http\Integrations\YandexGames\DTOs\FeedDto\FeedGamesByDeveloperDto;
use App\Http\Integrations\YandexGames\DTOs\PageInfoDto;
use App\Http\Integrations\YandexGames\Enums\FeedType;
use App\Http\Integrations\YandexGames\Values\Url;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use Saloon\Http\Response;

final readonly class GamesByDeveloperResponse implements Arrayable
{
    /**
     * @param (FeedGamesByDeveloperDto|FeedAdvDto)[] $feed
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

        if (! isset($json['feed'])) {
            throw new \Exception('Feed is be empty.');
        }

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
                FeedType::fromYandex($feedItem)->getStrategy()->dto($feedItem),
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
