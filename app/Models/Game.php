<?php

declare(strict_types=1);

namespace App\Models;

use App\Builders\GameBuilder;
use App\Models\Concerns\Games\HasGameRelationships;
use App\Models\Concerns\MorphsToSources;
use Database\Factories\GameFactory;
use Illuminate\Database\Eloquent\Attributes\UseEloquentBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[UseEloquentBuilder(GameBuilder::class)]
class Game extends Model
{
    /** @use HasFactory<GameFactory> */
    use HasFactory, HasGameRelationships, MorphsToSources;

    protected $fillable = [
        'slug',
        'title',
        'description',
        'developer_id',
        'released_at',
        'age_rating',
        'cis_score',
        'tag_ids',
        'category_ids',
        'reviews_count',
        'reviews_scores_stat',
        'reviews_scores_avg',
        'min_load_time_seconds',
    ];

    protected function casts(): array
    {
        return [
            'released_at' => 'datetime',
            'category_ids' => 'array',
            'tag_ids' => 'array',
            'reviews_scores_stat' => 'array',
        ];
    }

    // @mago-ignore lint:no-redundant-method-override
    public static function query(): GameBuilder
    {
        /** @var GameBuilder */
        return parent::query();
    }
}
