<?php

declare(strict_types=1);

namespace App\Builders\Concerns;

trait HasSyncScope
{
    public function notSyncedFor(string $interval = '1 hour'): static
    {
        return $this->where(
            static fn(self $query) => $query->whereNull('synced_at')->orWhere('synced_at', '<', now()->sub($interval))
        );
    }
}
