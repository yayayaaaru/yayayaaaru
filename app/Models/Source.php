<?php

declare(strict_types=1);

namespace App\Models;

use App\Casts\SourceNameCast;
use Database\Factories\SourceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    /** @use HasFactory<SourceFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'external_id',
    ];

    protected function casts(): array
    {
        return [
            'name' => SourceNameCast::class,
        ];
    }
}
