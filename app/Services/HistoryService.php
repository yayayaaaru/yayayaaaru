<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Contracts\Historable;
use App\Models\History;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class HistoryService
{
    public function getFieldTimeline(
        Historable|Model $historable,
        string $field,
        ?\DateTimeInterface $from = null,
    ) : Collection
    {
        return $historable->histories()
            ->when($from, static fn($q) => $q->where('fetched_at', '>=', $from))
            ->orderByDesc('fetched_at')
            ->orderByDesc('id')
            ->get(['data', 'fetched_at'])
            ->filter(static fn(History $h) => array_key_exists($field, $h->data))
            ->map(static fn(History $h) => [
                'date' => $h->fetched_at->toDateString(),
                'value' => $h->data[$field],
            ])
            ->values();
    }
}
