<?php

declare(strict_types=1);

function brand(): string
{
    return sprintf(
        '<b class="text-uppercase">%s<span class="text-danger">%s</span></b>',
        config('app.slug'),
        config('app.zone'),
    );
}
