<?php

declare(strict_types=1);

namespace App\Http\Integrations\YandexGames\DTOs;

final readonly class NavigationLinkDto
{
    public function __construct(
        public string $name,
        public string $url,
    )
    {
    }
}
