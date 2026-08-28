<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\Contracts\Historable;
use App\Models\History;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface HistoryRepository
{
    /**
     * @return Collection<int, History>
     */
    public function getForHistorable(Historable|Model $historable, ?\DateTimeInterface $from = null): Collection;
}
