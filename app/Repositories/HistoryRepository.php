<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Contracts\Historable;
use App\Repositories\Contracts\HistoryRepository as HistoryRepositoryContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

final class HistoryRepository implements HistoryRepositoryContract
{
    public function getForHistorable(Historable|Model $historable, ?\DateTimeInterface $from = null): Collection
    {
        return $historable->histories()
            ->when($from, static fn ($q) => $q->where('fetched_at', '>=', $from))
            ->orderByDesc('fetched_at')
            ->orderByDesc('id')
            ->get(['data', 'fetched_at']);
    }
}
