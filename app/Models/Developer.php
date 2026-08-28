<?php

declare(strict_types=1);

namespace App\Models;

use App\Builders\DeveloperBuilder;
use App\Http\Middleware\Contracts\HasRoutableSlug;
use App\Models\Concerns\Developers\HasDeveloperRelationships;
use App\Models\Concerns\MorphsToHistories;
use App\Models\Concerns\MorphsToSources;
use App\Models\Contracts\Historable;
use Database\Factories\DeveloperFactory;
use Illuminate\Database\Eloquent\Attributes\UseEloquentBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[UseEloquentBuilder(DeveloperBuilder::class)]
class Developer extends Model implements HasRoutableSlug, Historable
{
    /** @use HasFactory<DeveloperFactory> */
    use HasFactory, HasDeveloperRelationships, MorphsToSources, MorphsToHistories;

    protected $fillable = [
        'name',
        'slug',
        'synced_at',
        'removed_at',
    ];

    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'slug' => 'string',
            'name' => 'string',
            'views_count' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'synced_at' => 'datetime',
            'removed_at' => 'datetime',
        ];
    }

    // @mago-ignore lint:no-redundant-method-override
    public static function query(): DeveloperBuilder
    {
        /** @var DeveloperBuilder */
        return parent::query();
    }

    public function historizedAttributes(): array
    {
        return [
            'name',
        ];
    }

    public function getRouteSlug(): string
    {
        return $this->slug;
    }
}
