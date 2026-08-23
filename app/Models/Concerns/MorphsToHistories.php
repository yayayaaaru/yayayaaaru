<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Models\History;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait MorphsToHistories
{
    protected ?\DateTimeInterface $historyFetchedAt = null;

    public static function bootMorphsToHistories(): void
    {
        static::updated(static function (self $model) {
            $tracked = $model->historizedAttributes();
            $changed = array_intersect(array_keys($model->getChanges()), $tracked);

            if ($changed === []) {
                return;
            }

            $model->histories()->create([
                'data' => $model->only($changed),
                'fetched_at' => ($model->historyFetchedAt ?? now())->toDateTimeString(),
            ]);
        });
    }

    public function withHistoryFetchedAt(\DateTimeInterface $fetchedAt): static
    {
        $this->historyFetchedAt = $fetchedAt;

        return $this;
    }

    public function histories(): MorphMany
    {
        return $this->morphMany(History::class, 'historable');
    }

    abstract public function historizedAttributes(): array;
}
