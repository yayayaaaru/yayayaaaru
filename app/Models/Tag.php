<?php

declare(strict_types=1);

namespace App\Models;

use App\Builders\TagBuilder;
use App\Models\Concerns\MorphsToSources;
use Database\Factories\TagFactory;
use Illuminate\Database\Eloquent\Attributes\UseEloquentBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[UseEloquentBuilder(TagBuilder::class)]
class Tag extends Model
{
    /** @use HasFactory<TagFactory> */
    use HasFactory, MorphsToSources;

    protected $fillable = [
        'slug',
        'title',
    ];

    // @mago-ignore lint:no-redundant-method-override
    public static function query(): TagBuilder
    {
        /** @var TagBuilder */
        return parent::query();
    }
}
