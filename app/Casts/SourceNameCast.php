<?php

declare(strict_types=1);

namespace App\Casts;

use App\Enums\SourceName;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class SourceNameCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): SourceName
    {
        return SourceName::from($value);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): string
    {
        $name = $value instanceof SourceName ? $value : SourceName::tryFrom($value);

        return $name->value;
    }
}
