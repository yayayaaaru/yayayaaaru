<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\DTOs\SourceDto;
use App\Enums\SourceName;
use App\Http\Integrations\YandexGames\DTOs\GameDto\GameDto;
use App\Http\Integrations\YandexGames\Requests\GetGamesByIdsRequest;
use App\Http\Integrations\YandexGames\Responses\GamesByIdsResponse;
use App\Http\Integrations\YandexGames\YandexGamesConnector;
use App\Models\Category;
use App\Models\Game;
use App\Models\Tag;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

#[Signature('tags:yandex:games:sync')] // @todo
#[Description('Command description')]
class TagsYandexGamesSyncCommend extends Command
{
    private const int CHUNK_SIZE = 24;

    /**
     * Execute the console command.
     */
    public function handle(YandexGamesConnector $connector): int
    {
        Game::query()
            ->with('sources')
            ->orderByDesc('created_at')
            ->orderBy('id')
            ->chunk(
                self::CHUNK_SIZE,
                function (Collection $games) use ($connector) {

                    /** @var string[] $ids */
                    $ids = $games
                        ->map(
                            static fn(Game $game) => $game
                                ->sources
                                ->firstWhere('name', SourceName::YANDEXGAMES)
                                ->external_id
                        )
                        ->all();

                    $res = $connector->send(new GetGamesByIdsRequest($ids));

                    if (! $res->ok()) {
                        return;
                    }

                    /** @var GamesByIdsResponse $payload */
                    $payload = $res->dto();

                    $tags = $this->taxonomyByTags($payload);
                    $categories = $this->taxonomyByCategories($payload);

                    $this->withProgressBar(
                        $payload->games,
                        function (GameDto $gameDto) use ($games, $tags, $categories) {

                            /** @var Game|null $game */
                            $game = $games->first(
                                static fn(Game $game) => $game
                                        ->sources
                                        ->firstWhere('name', SourceName::YANDEXGAMES)
                                        ->external_id === (string)$gameDto->id->value
                            );

                            if (is_null($game)) {
                                return;
                            }

                            $gameTags = $this->filterByTags($gameDto, $tags);
                            $gameCategories = $this->filterByCategories($gameDto, $categories);

                            $game->tags()->sync($gameTags);
                            $game->categories()->sync($gameCategories);
                        }
                    );

                    $this->info(' success');
                }
            );

        return self::SUCCESS;
    }

    private function taxonomyByTags(GamesByIdsResponse $payload): Collection
    {
        return $this->taxonomyBy($payload, 'tagIds');
    }

    private function taxonomyByCategories(GamesByIdsResponse $payload): Collection
    {
        return $this->taxonomyBy($payload, 'categoryIds');
    }

    private function taxonomyBy(GamesByIdsResponse $payload, string $field): Collection
    {
        $query = match ($field) {
            'tagIds' => Tag::query(),
            'categoryIds' => Category::query(),
            default => throw new \InvalidArgumentException(),
        };

        /** @var Collection<int> $ids */
        $ids = collect($payload->games)->flatMap(static fn(GameDto $dto) => $dto->$field)->unique()->values();

        /** @var Collection<SourceDto> $sourceDtos */
        $sourceDtos = $ids->map(static fn(int $id) => new SourceDto(SourceName::YANDEXGAMES->value, (string)$id));

        return $query->with('sources')->whereHasSources($sourceDtos)->get();
    }

    private function filterByTags(GameDto $gameDto, Collection $tags): Collection
    {
        return $this->filterBy($gameDto, $tags, 'tagIds');
    }

    private function filterByCategories(GameDto $gameDto, Collection $categories): Collection
    {
        return $this->filterBy($gameDto, $categories, 'categoryIds');
    }

    private function filterBy(GameDto $gameDto, Collection $collection, string $field): Collection
    {
        $ids = collect($gameDto->$field)->map(static fn(int $id) => (string)$id);

        $res = $collection->filter(
            static fn(Tag|Category $entity) => $ids->contains(
                $entity->sources->firstWhere('name', SourceName::YANDEXGAMES)->external_id
            )
        );

        return $res->values();
    }
}
