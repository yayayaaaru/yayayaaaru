<?php

declare(strict_types=1);

namespace App\Models\Concerns\Games;

use App\Models\Category;
use App\Models\Developer;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasGameRelationships
{
    public function developer(): BelongsTo
    {
        return $this->belongsTo(Developer::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'game_tag', 'game_id', 'tag_id')->withTimestamps();
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'game_category', 'game_id', 'category_id')->withTimestamps();
    }
}
