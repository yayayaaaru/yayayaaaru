<?php

declare(strict_types=1);

namespace App\Models\Concerns\Categories;

use App\Models\Game;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasCategoryRelationships
{
    public function games(): BelongsToMany
    {
        return $this->belongsToMany(Game::class, 'game_category', 'category_id', 'game_id');
    }
}
