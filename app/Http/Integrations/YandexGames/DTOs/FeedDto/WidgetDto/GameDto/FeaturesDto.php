<?php

declare(strict_types=1);

namespace App\Http\Integrations\YandexGames\DTOs\FeedDto\WidgetDto\GameDto;

use App\Http\Integrations\YandexGames\Values\AgeRating;

final readonly class FeaturesDto
{
    public function __construct(
        public AgeRating $ageRating,
        public bool $userDataRequired,
    )
    {
    }
}
