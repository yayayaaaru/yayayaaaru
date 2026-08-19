<?php

declare(strict_types=1);

namespace App\Models;

use App\Builders\CategoryBuilder;
use App\Models\Concerns\MorphsToSources;
use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Attributes\UseEloquentBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[UseEloquentBuilder(CategoryBuilder::class)]
class Category extends Model
{
    /** @use HasFactory<CategoryFactory> */
    use HasFactory, MorphsToSources;

    protected $fillable = [
        'slug',
        'title',
    ];

    // @mago-ignore lint:no-redundant-method-override
    public static function query(): CategoryBuilder
    {
        /** @var CategoryBuilder */
        return parent::query();
    }
}
