<?php

declare(strict_types=1);

namespace App\Builders;

class GameBuilder extends SourceableBuilder
{
    protected function sourcesRelationName(): string
    {
        return 'sources';
    }
}
