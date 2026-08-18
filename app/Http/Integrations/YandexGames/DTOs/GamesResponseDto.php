<?php

declare(strict_types=1);

namespace App\Http\Integrations\YandexGames\DTOs;

use App\Http\Integrations\YandexGames\DTOs\FeedDto\FeedGridLayoutDto;
use App\Http\Integrations\YandexGames\Enums\FeedType;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use Saloon\Http\Response;

final readonly class GamesResponseDto implements Arrayable
{
    /**
     * @param FeedGridLayoutDto[] $feed
     * @param NavigationLinkDto[] $siteNavigationLinks
     */
    public function __construct(
        public array $feed,
        public PageInfoDto $pageInfo,
        public int $gamesWithPromos,
        public string $shareImage,
        public array $siteNavigationLinks,
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
        /** @var int $gamesWithPromos */
        $gamesWithPromos = $json['gamesWithPromos'];
        /** @var string $siteNavigationLinks */
        $shareImage = $json['shareImage'];
        /** @var array $siteNavigationLinks */
        $siteNavigationLinks = $json['siteNavigationLinks'];
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
            $gamesWithPromos,
            $shareImage,
            collect($siteNavigationLinks)->map(
                static fn(array $link) => new NavigationLinkDto(
                    $link['name'],
                    $link['url'],
                )
            )->toArray(),
            $gamesRequestId,
        );
    }

    public function toArray()
    {
        // TODO: Implement toArray() method.
    }
}
