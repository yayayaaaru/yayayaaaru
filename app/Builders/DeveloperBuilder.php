<?php

declare(strict_types=1);

namespace App\Builders;

class DeveloperBuilder extends SourceableBuilder
{
    protected function sourcesRelationName(): string
    {
        return 'sources';
    }
}
