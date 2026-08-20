<?php

declare(strict_types=1);

namespace App\Http\Integrations\YandexGames\Responses;

use App\Http\Integrations\YandexGames\DTOs\FeedDto\WidgetDto\GameDto\DeveloperDto;
use App\Http\Integrations\YandexGames\DTOs\FeedDto\WidgetDto\GameDto\RatingDto;
use App\Http\Integrations\YandexGames\DTOs\GameDto\CategoryDto;
use App\Http\Integrations\YandexGames\DTOs\GameDto\GameDto;
use App\Http\Integrations\YandexGames\Values\AppId;
use App\Http\Integrations\YandexGames\Values\Url;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Saloon\Http\Response;

final readonly class GamesByIdsResponse implements Arrayable
{
    public function __construct(
        public array $games,
    )
    {
    }

    public static function fromSaloonResponse(Response $response): self
    {
        $json = $response->json();

        /** @var array $games */
        $games = $json['games'];

        $res = collect();

        collect($games)->each(
            static fn(array $game): Collection => $res->add(
                new GameDto(
                    new DeveloperDto(
                        $game['developer']['id'],
                        $game['developer']['name'],
                    ),
                    $game['categoryIDs'],
                    $game['title'],
                    new AppId($game['appID']),
                    new RatingDto(
                        $game['ratingCount'],
                        $game['rating'],
                    ),
                    new Url($game['url']),
                    $game['categoriesNames'],
                    $game['description'],
                    $game['instruction'],
                    $game['seoDescription'],
                    $game['generatedTitle'] ?? null,
                    $game['seoTitle'],
                    $game['features'], // @todo
                    $game['tagIDs'],
                    $game['score'],
                    $game['minLoadTime'] ?? 0,
                    Carbon::parse($game['firstPublished']),
                    $game['extraFeatures'], // @todo
                    $game['badge'],
                    array_map(
                        static fn(int $id, string $name): CategoryDto => new CategoryDto($id, $name),
                        $game['categoryIDs'],
                        $game['categoriesNames']
                    ),
                )
            )
        );

        return new self(
            $res->toArray()
        );
    }

    public function toArray()
    {
        // TODO: Implement toArray() method.
    }
}
