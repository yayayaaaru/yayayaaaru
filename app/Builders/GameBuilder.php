<?php

declare(strict_types=1);

namespace App\Builders;

use App\Builders\Concerns\HasSyncScope;
use App\Enums\SourceName as Name;

class GameBuilder extends SourceableBuilder
{
    use HasSyncScope;

    protected function sourcesRelationName(): string
    {
        return 'sources';
    }

    // @todo ?
    public function whereSourceFor(Name $name): self
    {
        return $this->whereHas('sources', static fn($q) => $q->where('name', $name));
    }
}
