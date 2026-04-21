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
            'respexp' => [
                'route' => 'respexp',
                'label' => 'Resp. exploitation',
                'title' => 'Responsable exploitation',
            ],
            'juridique' => [
                'route' => 'juridique',
                'label' => 'Resp. juridique',
                'title' => 'Responsable juridique',
            ],
            'analyste-juridique' => [
                'route' => 'analyste-juridique',
                'label' => 'Analyste juridique',
                'title' => 'Analyste juridique',
            ],
            'analyste-credit' => [
                'route' => 'analyste-credit',
                'label' => 'Analyste crédit',
                'title' => 'Analyste crédit',
            ],
            'analyste-risques' => [
                'route' => 'analyste-risques',
                'label' => 'Analyste risques',
                'title' => 'Analyste risques',
            ],
            'respaud' => [
                'route' => 'respaud',
                'label' => 'Resp. audit',
                'title' => 'Responsable audit interne',
            ],
            'respci' => [
                'route' => 'respci',
                'label' => 'Resp. contrôle',
                'title' => 'Responsable contrôle interne',
            ],
            'reng' => [
                'route' => 'reng',
                'label' => 'Resp. engagements',
                'title' => 'Responsable engagements',
            ],
            'rerx' => [
                'route' => 'rerx',
                'label' => 'Resp. risques',
                'title' => 'Responsable risques',
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
            'chef-filiere' => [
                'route' => 'chef-filiere',
                'label' => 'Chef de filière',
                'title' => 'Chef de filière',
            ],
            default => [
                'route' => 'role-space',
                'label' => 'Espace',
                'title' => 'Espace rôle',
            ],
        };
    }
}
