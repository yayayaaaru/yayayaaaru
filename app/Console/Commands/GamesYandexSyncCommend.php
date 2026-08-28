<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\DTOs\SourceDto;
use App\Enums\SourceName;
use App\Http\Integrations\YandexGames\DTOs\GameDto\GameDto;
use App\Http\Integrations\YandexGames\Requests\GetGamesByIdsRequest;
use App\Http\Integrations\YandexGames\Responses\GamesByIdsResponse;
use App\Http\Integrations\YandexGames\Values\AppId;
use App\Http\Integrations\YandexGames\YandexGamesConnector;
use App\Models\Category;
use App\Models\Game;
use App\Models\Tag;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

#[Signature('games:yandex:sync')]
#[Description('Command description')]
class GamesYandexSyncCommend extends Command
{
    private const int CHUNK_SIZE = 24;

    /**
     * Execute the console command.
     */
    public function handle(YandexGamesConnector $connector): int
    {
        Game::query()
            ->with('sources')
            ->notSyncedFor('3 hours') // @todo
            ->whereNull('removed_at')
            ->orderBy('id')
            ->chunkById(
                self::CHUNK_SIZE,
                function (Collection $games) use ($connector) {

                    $syncedAt = now();

                    /** @var string[] $externalIds */
                    $externalIds = $games
                        ->map(
                            static fn(Game $game) => $game
                                ->sources
                                ->firstWhere('name', SourceName::YANDEXGAMES)
                                ->external_id
                        )
                        ->all();

                    $res = $connector->send(
                        new GetGamesByIdsRequest($externalIds)
                    );

                    if (! $res->ok()) {
                        return;
                    }

                    /** @var GamesByIdsResponse $payload */
                    $payload = $res->dto();

                    $deletedIds = collect(
                        collect($externalIds)->diff(
                            collect($payload->games)->pluck('id')->map(static fn(AppId $id) => (string)$id->value)
                        )
                    );

                    if ($deletedIds->isNotEmpty()) {

                        $removedAt = now();

                        foreach ($deletedIds as $deletedId) {

                            $targetSource = new SourceDto(
                                SourceName::YANDEXGAMES,
                                $deletedId,
                            );

                            Game::query()
                                ->whereNull('removed_at')
                                ->whereHasSources([$targetSource])
                                ->first()
                                ->withHistoryFetchedAt($syncedAt)
                                ->update(['removed_at' => $removedAt]);
                        }
                    }

                    $tags = $this->taxonomyByTags($payload);
                    $categories = $this->taxonomyByCategories($payload);

                    $this->withProgressBar(
                        $payload->games,
                        function (GameDto $gameDto) use ($games, $tags, $categories, $syncedAt) {

                            $fetchedAt = now();

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

                            $game->withHistoryFetchedAt($fetchedAt)->update([
                                'title' => $gameDto->title,
                            ]);

                            $game->withHistoryFetchedAt($fetchedAt)->update([
                                'description' => $gameDto->description,
                            ]);

                            $game->withHistoryFetchedAt($fetchedAt)->update([
                                'age_rating' => $gameDto->features['age_rating'], // @todo
                            ]);

                            $game->withHistoryFetchedAt($fetchedAt)->update([
                                'cis_score' => $gameDto->gqRating,
                            ]);

                            $scores = $gameDto->score;
                            $game->withHistoryFetchedAt($fetchedAt)->update([
                                'reviews_count' => $scores->count(),
                                'reviews_scores_stat' => $scores->all(),
                                'reviews_scores_avg' => $scores->average(),
                            ]);

                            $game->withHistoryFetchedAt($fetchedAt)->update([
                                'min_load_time_seconds' => $gameDto->minLoadTime,
                            ]);

                            $tagIds = $gameTags->pluck('id');
                            $game->withHistoryFetchedAt($fetchedAt)->update([
                                'tag_ids' => $tagIds->all(),
                            ]);

                            $categoryIds = $gameCategories->pluck('id');
                            $game->withHistoryFetchedAt($fetchedAt)->update([
                                'category_ids' => $categoryIds->all(),
                            ]);

                            if (is_null($game->released_at)) {
                                $game->update(['released_at' => $gameDto->firstPublished]);
                            }

                            $game->timestamps = false;

                            $game->withHistoryFetchedAt($fetchedAt)->update([
                                'synced_at' => $syncedAt,
                            ]);
                        }
                    );

                    $this->info(' success');
                },
                column: 'id'
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
        $sourceDtos = $ids->map(static fn(int $id) => new SourceDto(
            SourceName::YANDEXGAMES,
            (string)$id,
        ));

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

        return $res->sortBy('id')->values();
    }
}
