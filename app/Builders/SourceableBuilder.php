<?php

declare(strict_types=1);

namespace App\Builders;

use App\DTOs\SourceDto;
use App\Enums\SourceName as Source;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * @template TModel of Model
 * @extends Builder<TModel>
 */
abstract class SourceableBuilder extends Builder
{
    abstract protected function sourcesRelationName(): string;

    public function whereHasSources(iterable $sources): self
    {
        $sources = $this->toCollection($sources);

        if ($sources->isEmpty()) {
            // нет источников — гарантированно пустой результат
            return $this->whereRaw('1 = 0');
        }

        return $this->whereHas(
            $this->sourcesRelationName(),
            fn (Builder $query) => $this->applySourcesMatch($query, $sources),
        );
    }

    /**
     * @param iterable<SourceDto> $sources
     * @param array<Source> $allowedNames
     */
    public function whereHasSourcesByNames(iterable $sources, array $allowedNames): self
    {
        $sources = $this->toCollection($sources)
            ->filter(
                static fn (SourceDto $source) => in_array(
                    $source->name,
                    $allowedNames,
                    true,
                ),
            );

        return $this->whereHasSources($sources);
    }

    public function whereHasSourceNamed(Source $source): self
    {
        return $this->whereHas(
            $this->sourcesRelationName(),
            static fn (Builder $query) => $query->where('name', $source->value),
        );
    }

    private function applySourcesMatch(Builder $query, Collection $sources): void
    {
        $query->where(
            function (Builder $query) use ($sources): void {
                foreach ($sources as $source) {
                    $query->orWhere(
                        fn (Builder $query) => $this->matchSource($query, $source),
                    );
                }
            },
        );
    }

    private function matchSource(Builder $query, SourceDto $source): void
    {
        $query
            ->where('name', $source->name->value)
            ->where('external_id', $source->externalId);
    }

    private function toCollection(iterable $sources): Collection
    {
        return $sources instanceof Collection ? $sources : collect($sources);
    }
}
