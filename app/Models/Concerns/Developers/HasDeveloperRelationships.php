<?php

declare(strict_types=1);

namespace App\Models\Concerns\Developers;

use App\Models\Game;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasDeveloperRelationships
{
    public function games(): HasMany
    {
        return $this->hasMany(Game::class);
    }
}
