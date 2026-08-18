<?php

declare(strict_types=1);

namespace App\Builders;

class TagBuilder extends SourceableBuilder
{
    protected function sourcesRelationName(): string
    {
        return 'sources';
    }
}
