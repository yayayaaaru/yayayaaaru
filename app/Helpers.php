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

/**
 * Simple, non-cryptographically secure hash function for strings.
 * This is used for generating hashes for identifiers that do not require high security.
 */
function simple_hash(?string $string): string
{
    return md5("noilty-hash:$string");
}

/**
 * @param string|int ...$parts
 */
function cache_key(...$parts): string
{
    return simple_hash(implode('.', $parts));
}
