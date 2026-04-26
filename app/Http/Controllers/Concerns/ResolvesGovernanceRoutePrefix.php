<?php

namespace App\Http\Controllers\Concerns;

trait ResolvesGovernanceRoutePrefix
{
    protected function governanceRoutePrefix(): string
    {
        $name = request()->route()?->getName() ?? '';

        return match (true) {
            str_starts_with($name, 'dg.') => 'dg',
            str_starts_with($name, 'dga.') => 'dga',
            default => 'ca',
        };
    }

    protected function governanceLayout(): string
    {
        $p = $this->governanceRoutePrefix();

        return in_array($p, ['dg', 'dga'], true) ? 'Layouts.'.$p : 'Layouts.ca';
    }
}
