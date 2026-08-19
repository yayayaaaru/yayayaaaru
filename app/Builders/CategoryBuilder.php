<?php

declare(strict_types=1);

namespace App\Builders;

class CategoryBuilder extends SourceableBuilder
{
    protected function sourcesRelationName(): string
    {
        return 'sources';
    }
}
