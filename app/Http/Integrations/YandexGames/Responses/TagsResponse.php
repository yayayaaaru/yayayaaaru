<?php

declare(strict_types=1);

namespace App\Http\Integrations\YandexGames\Responses;

use App\Http\Integrations\YandexGames\DTOs\TagDto\InfoDto;
use App\Http\Integrations\YandexGames\DTOs\TagDto\StatDto;
use App\Http\Integrations\YandexGames\DTOs\TagDto\TagDto;
use Illuminate\Contracts\Support\Arrayable;
use Saloon\Http\Response;

final readonly class TagsResponse implements Arrayable
{
    public function __construct(
        public array $tags,
    )
    {
    }

    public static function fromSaloonResponse(Response $response): self
    {
        $json = $response->json();

        /** @var array $tags */
        $tags = $json['tags'];

        return new self(
            collect($tags)->map(
                static fn(array $tag) => new TagDto(
                    $tag['id'],
                    $tag['title'],
                    $tag['description'] ?? null,
                    $tag['seoTitleGenerated'],
                    $tag['seoDescriptionGenerated'],
                    $tag['slug'],
                    new InfoDto(
                        $tag['info']['games_count']
                    ),
                    $tag['isService'],
                    new StatDto(
                        $tag['stat']['rating'],
                        $tag['stat']['ratingCount'],
                    ),
                )
            )->toArray(),
        );
    }

    public function toArray()
    {
        // TODO: Implement toArray() method.
    }
}
