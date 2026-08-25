<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\HistoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class History extends Model
{
    /** @use HasFactory<HistoryFactory> */
    use HasFactory;

    protected $fillable = [
        'data',
        'fetched_at',
    ];

    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'historyable_type' => 'string',
            'historyable_id' => 'integer',
            'data' => 'array',
            'fetched_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function historable(): MorphTo
    {
        return $this->morphTo();
    }
}
