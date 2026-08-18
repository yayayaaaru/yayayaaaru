<?php

declare(strict_types=1);

namespace App\Http\Integrations\YandexGames;

use App\Http\Integrations\YandexGames\Contracts\FeedStrategy;
use App\Http\Integrations\YandexGames\DTOs\FeedDto\FeedGridLayoutDto;
use App\Http\Integrations\YandexGames\DTOs\FeedDto\WidgetDto\ConfigDto;
use App\Http\Integrations\YandexGames\DTOs\FeedDto\WidgetDto\GameDto\BadgeDto;
use App\Http\Integrations\YandexGames\DTOs\FeedDto\WidgetDto\GameDto\DeveloperDto;
use App\Http\Integrations\YandexGames\DTOs\FeedDto\WidgetDto\GameDto\GameDto;
use App\Http\Integrations\YandexGames\DTOs\FeedDto\WidgetDto\GameDto\GameFeaturesDto;
use App\Http\Integrations\YandexGames\DTOs\FeedDto\WidgetDto\GameDto\GameMediaDto\GameMediaDto;
use App\Http\Integrations\YandexGames\DTOs\FeedDto\WidgetDto\GameDto\GameMediaDto\ImageDto;
use App\Http\Integrations\YandexGames\DTOs\FeedDto\WidgetDto\GameDto\GameMediaDto\VideoDto;
use App\Http\Integrations\YandexGames\DTOs\FeedDto\WidgetDto\GameDto\GridPositionDto;
use App\Http\Integrations\YandexGames\DTOs\FeedDto\WidgetDto\GameDto\RatingDto;
use App\Http\Integrations\YandexGames\DTOs\FeedDto\WidgetDto\WidgetDto;
use App\Http\Integrations\YandexGames\Enums\FeedType;
use App\Http\Integrations\YandexGames\Values\AgeRating;
use App\Http\Integrations\YandexGames\Values\AppId;
use App\Http\Integrations\YandexGames\Values\AppSlug;
use App\Http\Integrations\YandexGames\Values\Url;
use App\Http\Integrations\YandexGames\Values\VideoDimensions;

final readonly class FeedGridLayoutStrategy implements FeedStrategy
{
    public function dto(array $context): FeedGridLayoutDto
    {
        return new FeedGridLayoutDto(
            FeedType::fromYandex($context),
            $context['baseRuleIndex'],
            $context['blockLabel'],
            collect($context['widgets'])->map(
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
            $context['isFromFirstPage'],
            $context['requestId']
        );
    }
}
