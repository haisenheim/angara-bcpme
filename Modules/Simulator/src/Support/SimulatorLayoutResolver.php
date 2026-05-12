<?php

namespace Modules\Simulator\Support;

use App\Models\User;
use App\Support\UserWorkspaceContextResolver;

/**
 * @deprecated Utiliser {@see UserWorkspaceContextResolver::layout()} ; conservé pour compatibilité des imports existants.
 */
final class SimulatorLayoutResolver
{
    public static function resolve(?User $user): string
    {
        return UserWorkspaceContextResolver::layout($user);
    }
}
