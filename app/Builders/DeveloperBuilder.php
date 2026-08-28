<?php

declare(strict_types=1);

namespace App\Builders;

use App\Builders\Concerns\HasSyncScope;

class DeveloperBuilder extends SourceableBuilder
{
    use HasSyncScope;

    protected function sourcesRelationName(): string
    {
        return 'sources';
    }
}
