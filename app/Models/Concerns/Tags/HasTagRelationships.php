<?php

declare(strict_types=1);

namespace App\Models\Concerns\Tags;

use App\Models\Game;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasTagRelationships
{
    public function games(): BelongsToMany
    {
        return $this->belongsToMany(Game::class, 'game_tag', 'tag_id', 'game_id');
    }
}
