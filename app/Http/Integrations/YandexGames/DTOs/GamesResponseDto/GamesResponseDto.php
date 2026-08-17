<?php

declare(strict_types=1);

namespace App\Http\Integrations\YandexGames\DTOs\GamesResponseDto;

use App\Http\Integrations\YandexGames\DTOs\GamesResponseDto\FeedDto\FeedDto;
use App\Http\Integrations\YandexGames\DTOs\GamesResponseDto\FeedDto\WidgetDto\ConfigDto;
use App\Http\Integrations\YandexGames\DTOs\GamesResponseDto\FeedDto\WidgetDto\GameDto\BadgeDto;
use App\Http\Integrations\YandexGames\DTOs\GamesResponseDto\FeedDto\WidgetDto\GameDto\DeveloperDto;
use App\Http\Integrations\YandexGames\DTOs\GamesResponseDto\FeedDto\WidgetDto\GameDto\GameDto;
use App\Http\Integrations\YandexGames\DTOs\GamesResponseDto\FeedDto\WidgetDto\GameDto\GameFeaturesDto;
use App\Http\Integrations\YandexGames\DTOs\GamesResponseDto\FeedDto\WidgetDto\GameDto\GameMediaDto\GameMediaDto;
use App\Http\Integrations\YandexGames\DTOs\GamesResponseDto\FeedDto\WidgetDto\GameDto\GameMediaDto\ImageDto;
use App\Http\Integrations\YandexGames\DTOs\GamesResponseDto\FeedDto\WidgetDto\GameDto\GameMediaDto\VideoDto;
use App\Http\Integrations\YandexGames\DTOs\GamesResponseDto\FeedDto\WidgetDto\GameDto\GridPositionDto;
use App\Http\Integrations\YandexGames\DTOs\GamesResponseDto\FeedDto\WidgetDto\GameDto\RatingDto;
use App\Http\Integrations\YandexGames\DTOs\GamesResponseDto\FeedDto\WidgetDto\WidgetDto;
use App\Http\Integrations\YandexGames\DTOs\Values\AgeRating;
use App\Http\Integrations\YandexGames\DTOs\Values\AppId;
use App\Http\Integrations\YandexGames\DTOs\Values\AppSlug;
use App\Http\Integrations\YandexGames\DTOs\Values\Url;
use App\Http\Integrations\YandexGames\DTOs\Values\VideoDimensions;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use Saloon\Http\Response;

final readonly class GamesResponseDto implements Arrayable
{
    /**
     * @param FeedDto[] $feed
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
                new FeedDto(
                    $feedItem['type'],
                    $feedItem['baseRuleIndex'],
                    $feedItem['blockLabel'],
                    collect($feedItem['widgets'])->map(
                        static fn (array $widget) => new WidgetDto(
                            $widget['type'],
                            new ConfigDto($widget['config']['size']),
                            new GameDto(
                                new DeveloperDto(
                                    $widget['data']['developer']['id'],
                                    $widget['data']['developer']['name'],
                                ),
                                $widget['data']['categoryIDs'],
                                $widget['data']['title'],
                                new AppSlug($widget['data']['appSlug']),
                                new AppId($widget['data']['appID']),
                                new RatingDto(
                                    $widget['data']['ratingCount'],
                                    $widget['data']['rating'],
                                ),
                                new GameMediaDto(
                                    new ImageDto(
                                        $widget['data']['media']['cover']['prefix-url'],
                                        $widget['data']['media']['cover']['mainColor'],
                                    ),
                                    new ImageDto(
                                        $widget['data']['media']['icon']['prefix-url'],
                                        $widget['data']['media']['icon']['mainColor'],
                                    ),
                                    collect($widget['data']['media']['videos'])->map(
                                        static fn(array $video) => new VideoDto(
                                            new Url($video['embedUrl']),
                                            new Url($video['thumbnailUrl']),
                                            new Url($video['thumbnailUrlPrefix']),
                                            new Url($video['previewUrl']),
                                            new Url($video['mp4StreamUrl']),
                                            new VideoDimensions(
                                                $video['width'],
                                                $video['height'],
                                            ),
                                        )
                                    )->toArray(),
                                ),
                                $widget['data']['tagIDs'],
                                new GameFeaturesDto(
                                    new AgeRating($widget['data']['features']['age_rating']),
                                    $widget['data']['features']['user_data_required'],
                                ),
                                new BadgeDto(
                                    $widget['data']['badge']['badgeTitle'],
                                    $widget['data']['badge']['badgeType'],
                                ),
                                new GridPositionDto(
                                    $widget['data']['column'],
                                    $widget['data']['row'],
                                ),
                                $widget['data']['requestId'],
                            ),
                        )
                    )->toArray(),
                    $feedItem['isFromFirstPage'],
                    $feedItem['requestId']
                )
            )
        );

        dd($res);
    }

    public function toArray()
    {
        // TODO: Implement toArray() method.
    }
}
