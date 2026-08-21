<?php

declare(strict_types=1);

namespace App\Http\Integrations\YandexGames\Values;

final readonly class Score
{
    private const array DEFAULT_STARS = [1, 2, 3, 4, 5];

    /** @var array<int, int> */
    private array $scores;

    /**
     * @param array<int|string, int>|null $scores
     */
    public function __construct(array|null $scores)
    {
        $default = array_fill_keys(self::DEFAULT_STARS, 0);

        $sanitizedScores = [];
        foreach ($scores ?? [] as $key => $value) {
            $stars = (int) $key;

            if (!in_array($stars, self::DEFAULT_STARS, true)) {
                continue;
            }

            $sanitizedScores[$stars] = max(0, (int) $value);
        }

        $merged = array_replace($default, $sanitizedScores);
        ksort($merged);

        $this->scores = $merged;
    }

    /**
     * @return array<int, int>
     */
    public function all(): array
    {
        return $this->scores;
    }

    public function count(): int
    {
        return array_sum($this->scores);
    }

    public function average(int $precision = 3): float
    {
        $reviewsCount = $this->count();

        if ($reviewsCount === 0) {
            return 0.00;
        }

        $pointsTotal = 0;
        foreach ($this->scores as $stars => $count) {
            $pointsTotal += $stars * $count;
        }

        return round($pointsTotal / $reviewsCount, $precision);
    }
}
