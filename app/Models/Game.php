<?php

declare(strict_types=1);

namespace App\Models;

use App\Builders\GameBuilder;
use App\Http\Middleware\Contracts\HasRoutableSlug;
use App\Models\Concerns\Games\HasGameRelationships;
use App\Models\Concerns\MorphsToHistories;
use App\Models\Concerns\MorphsToSources;
use App\Models\Contracts\Historable;
use CyrildeWit\EloquentViewable\Contracts\Viewable;
use CyrildeWit\EloquentViewable\InteractsWithViews;
use Database\Factories\GameFactory;
use Illuminate\Database\Eloquent\Attributes\UseEloquentBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[UseEloquentBuilder(GameBuilder::class)]
class Game extends Model implements HasRoutableSlug, Historable, Viewable
{
    /** @use HasFactory<GameFactory> */
    use HasFactory, HasGameRelationships, MorphsToSources, MorphsToHistories, InteractsWithViews;

    protected $fillable = [
        'developer_id',
        'slug',
        'title',
        'description',
        'age_rating',
        'cis_score',
        'reviews_count',
        'reviews_scores_stat',
        'reviews_scores_avg',
        'min_load_time_seconds',
        'tag_ids',
        'category_ids',
        'views_count',
        'released_at',
        'removed_at',
        'synced_at',
    ];

    protected function casts(): array
    {
        return [
            'developer_id' => 'integer',
            'slug' => 'string',
            'title' => 'string',
            'description' => 'string',
            'age_rating' => 'string',
            'cis_score' => 'integer',
            'reviews_count' => 'integer',
            'reviews_scores_stat' => 'array',
            'reviews_scores_avg' => 'float',
            'min_load_time_seconds' => 'float',
            'tag_ids' => 'array',
            'category_ids' => 'array',
            'released_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'removed_at' => 'datetime',
            'synced_at' => 'datetime',
        ];
    }

    // @mago-ignore lint:no-redundant-method-override
    public static function query(): GameBuilder
    {
        /** @var GameBuilder */
        return parent::query();
    }

    public function historizedAttributes(): array
    {
        return [
            'age_rating',
            'reviews_count',
            'reviews_scores_stat',
            'reviews_scores_avg',
            'min_load_time_seconds',
            'cis_score',
            'tag_ids',
            'category_ids',
        ];
    }

    public function getRouteSlug(): string
    {
        return $this->slug;
    }
}
