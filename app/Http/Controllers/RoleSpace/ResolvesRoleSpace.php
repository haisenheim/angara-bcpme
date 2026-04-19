<?php

namespace App\Http\Controllers\RoleSpace;

trait ResolvesRoleSpace
{
    protected function resolveSpace(): array
    {
        $routeName = request()->route()?->getName() ?? '';
        $prefix = strtok($routeName, '.');

        return match ($prefix) {
            'pca' => [
                'route' => 'pca',
                'label' => 'PCA',
                'title' => 'Président du conseil d\'administration',
            ],
            'administrateur' => [
                'route' => 'administrateur',
                'label' => 'Administrateur',
                'title' => 'Administrateur',
            ],
            'dg' => [
                'route' => 'dg',
                'label' => 'DG',
                'title' => 'Directeur général',
            ],
            'dga' => [
                'route' => 'dga',
                'label' => 'DGA',
                'title' => 'Directeur général adjoint',
            ],
            'controleur' => [
                'route' => 'controleur',
                'label' => 'Contrôleur',
                'title' => 'Contrôleur',
            ],
            'auditeur' => [
                'route' => 'auditeur',
                'label' => 'Auditeur',
                'title' => 'Auditeur',
            ],
            default => [
                'route' => 'role-space',
                'label' => 'Espace',
                'title' => 'Espace rôle',
            ],
        };
    }
}
