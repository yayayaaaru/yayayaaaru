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
        'rating',
    ];

    // @mago-ignore lint:no-redundant-method-override
    public static function query(): GameBuilder
    {
        /** @var GameBuilder */
        return parent::query();
    }
}
