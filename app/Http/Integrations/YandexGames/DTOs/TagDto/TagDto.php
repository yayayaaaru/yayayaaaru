<?php

declare(strict_types=1);

namespace App\Http\Integrations\YandexGames\DTOs\TagDto;

final readonly class TagDto
{
    public function __construct(
        public int $id,
        public string $title,
        public string|null $description,
        public string $seoTitleGenerated,
        public string $seoDescriptionGenerated,
        public string $slug,
        public InfoDto $info,
        public bool $isService,
        public StatDto $stat,
    )
    {
    }
}
