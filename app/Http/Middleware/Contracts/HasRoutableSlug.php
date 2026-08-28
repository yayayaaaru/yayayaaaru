<?php

declare(strict_types=1);

namespace App\Http\Middleware\Contracts;

interface HasRoutableSlug
{
    public function getRouteSlug(): string;
}
