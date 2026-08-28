<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Contracts\Historable;
use App\Models\History;
use App\Repositories\Contracts\HistoryRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

final readonly class HistoryService
{
    public function __construct(
        private HistoryRepository $historyRepository,
    )
    {
    }

    public function getFieldTimeline(
        Historable|Model $historable,
        string $field,
        ?\DateTimeInterface $from = null,
    ) : Collection {
        return $this->getFieldsTimeline($historable, [$field], $from)->get($field, collect());
    }

    /**
     * @param array<int, string> $fields
     * @return Collection<string, Collection<int, array{date: string, value: mixed}>>
     */
    public function getFieldsTimeline(
        Historable|Model $historable,
        array $fields,
        ?\DateTimeInterface $from = null,
    ) : Collection {
        $this->assertFieldsAreHistorized($historable, $fields);

        $histories = $this->historyRepository->getForHistorable($historable, $from);

        return collect($fields)->mapWithKeys(
            fn (string $field) => [$field => $this->buildTimelineForField($histories, $field)],
        );
    }

    /**
     * @return Collection<string, Collection<int, array{date: string, value: mixed}>>
     */
    public function getAllFieldsTimeline(
        Historable|Model $historable,
        ?\DateTimeInterface $from = null,
    ) : Collection {
        return $this->getFieldsTimeline($historable, $historable->historizedAttributes(), $from);
    }

    /**
     * @param Collection<int, History> $histories
     * @return Collection<int, array{date: string, value: mixed}>
     */
    private function buildTimelineForField(Collection $histories, string $field): Collection
    {
        return $histories
            ->filter(static fn (History $h) => array_key_exists($field, $h->data))
            ->map(static fn (History $h) => [
                'date' => $h->fetched_at->toDateString(),
                'value' => $h->data[$field],
            ])
            ->values();
    }

    /**
     * @param array<int, string> $fields
     */
    private function assertFieldsAreHistorized(Historable|Model $historable, array $fields): void
    {
        $unknown = array_diff($fields, $historable->historizedAttributes());

        if ($unknown !== []) {
            throw new \InvalidArgumentException(sprintf(
                '%s does not track history for field(s): %s',
                $historable::class,
                implode(', ', $unknown),
            ));
        }
    }
}
