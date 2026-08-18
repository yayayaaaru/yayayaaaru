<?php

declare(strict_types=1);

namespace App\Models;

use App\Builders\DeveloperBuilder;
use App\Models\Concerns\Developers\HasDeveloperRelationships;
use App\Models\Concerns\MorphsToSources;
use Database\Factories\DeveloperFactory;
use Illuminate\Database\Eloquent\Attributes\UseEloquentBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[UseEloquentBuilder(DeveloperBuilder::class)]
class Developer extends Model
{
    /** @use HasFactory<DeveloperFactory> */
    use HasFactory, HasDeveloperRelationships, MorphsToSources;

    protected $fillable = [
        'name',
        'slug',
    ];

    // @mago-ignore lint:no-redundant-method-override
    public static function query(): DeveloperBuilder
    {
        /** @var DeveloperBuilder */
        return parent::query();
    }
}
