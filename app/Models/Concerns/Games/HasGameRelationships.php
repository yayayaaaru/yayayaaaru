<?php

declare(strict_types=1);

namespace App\Models\Concerns\Games;

use App\Models\Developer;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasGameRelationships
{
    public function developer(): BelongsTo
    {
        return $this->belongsTo(Developer::class);
    }
}
