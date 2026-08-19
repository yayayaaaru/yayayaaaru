<?php

declare(strict_types=1);

namespace App\Http\Integrations\YandexGames\DTOs\FeedDto\ItemDto;

use App\Http\Integrations\YandexGames\Values\Url;

final readonly class DeveloperDto
{
    public function __construct(
        public int $id,
        public string $name,
        public Url|null $logoUrl,
    )
    {
    }
}
