<?php

declare(strict_types=1);

namespace App\Models;

use App\Builders\TagBuilder;
use App\Http\Middleware\Contracts\HasRoutableSlug;
use App\Models\Concerns\MorphsToSources;
use App\Models\Concerns\Tags\HasTagRelationships;
use CyrildeWit\EloquentViewable\Contracts\Viewable;
use CyrildeWit\EloquentViewable\InteractsWithViews;
use Database\Factories\TagFactory;
use Illuminate\Database\Eloquent\Attributes\UseEloquentBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[UseEloquentBuilder(TagBuilder::class)]
class Tag extends Model implements HasRoutableSlug, Viewable
{
    /** @use HasFactory<TagFactory> */
    use HasFactory, HasTagRelationships, MorphsToSources, InteractsWithViews;

    protected $fillable = [
        'slug',
        'title',
    ];

    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'slug' => 'string',
            'title' => 'string',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    // @mago-ignore lint:no-redundant-method-override
    public static function query(): TagBuilder
    {
        /** @var TagBuilder */
        return parent::query();
    }

    public function getRouteSlug(): string
    {
        return $this->slug;
    }
}
