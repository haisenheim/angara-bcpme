<?php

/**
 * Catalogue des exports issue de la grille des engagements.
 *
 * Chaque entrée peut être activée (`enabled`) lorsque le rendu PDF / Excel est prêt.
 * Les données proviennent toujours du même référentiel que la grille (catégories + lignes).
 */
return [
    'reports' => [
        [
            'id' => 'grille_complete',
            'label' => 'Grille complète',
            'description' => 'Sections, rubriques, produits et lignes — même structure que à l’écran.',
            'enabled' => true,
            'pdf' => true,
            'xlsx' => true,
        ],
        [
            'id' => 'synthese_par_partenaire',
            'label' => 'Synthèse par partenaire',
            'description' => 'Vue agrégée par banque / EMF / autre (données grille).',
            'enabled' => false,
            'pdf' => true,
            'xlsx' => true,
        ],
        [
            'id' => 'focus_impayes',
            'label' => 'Focus impayés',
            'description' => 'Extraction des lignes avec encours impayés.',
            'enabled' => false,
            'pdf' => true,
            'xlsx' => true,
        ],
    ],
];
