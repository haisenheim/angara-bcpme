<?php

namespace App\Http\Controllers\Concerns;

use Closure;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Rendu du tableau de bord générique (page Inertia Dashboard/Index) pour les espaces dont les
 * indicateurs sont calculés par leur propre contrôleur (getStats / getTodos existants).
 *
 * Les tâches sont normalisées au format : token, entreprise, programmes, when_human, kind, href.
 *
 * Options (toutes facultatives) :
 *  - subtitle     : sous-titre de la page ;
 *  - todosTitle   : titre de la carte des tâches ; todosLink : ['label' => …, 'href' => …] ;
 *  - plainLinks   : liens en <a> classiques (cibles encore en Blade) au lieu de liens Inertia ;
 *  - widgets      : description des widgets (voir Components/dashboard/DashboardWidget.vue) ;
 *  - widgetData   : Closure(): array — valeurs des widgets indexées par clé, chargées en différé
 *                   (groupe « widgets », en parallèle des indicateurs).
 */
trait RendersInertiaDashboard
{
    /**
     * @param  array<int, array{key: string, label: string, icon: string, href?: ?string, tone?: string, hint?: string}>  $kpis
     * @param  Closure(): array  $stats
     * @param  Closure(): array|null  $todos
     * @param  array{subtitle?: string, todosTitle?: string, todosLink?: array, plainLinks?: bool, widgets?: array, widgetData?: Closure}  $options
     */
    protected function renderInertiaDashboard(string $title, array $kpis, Closure $stats, ?Closure $todos = null, array $options = []): Response
    {
        $widgets = array_values($options['widgets'] ?? []);
        $widgetData = $options['widgetData'] ?? null;

        $props = [
            'title' => $title,
            'kpis' => $kpis,
            'hasTodos' => $todos !== null,
            'hasInsights' => false,
            'stats' => Inertia::defer($stats),
            'todos' => Inertia::defer($todos ?? fn () => []),
            'insights' => null,
            'widgets' => $widgets,
            'widgetData' => $widgetData instanceof Closure ? Inertia::defer($widgetData, 'widgets') : null,
            'plainLinks' => (bool) ($options['plainLinks'] ?? false),
            'breadcrumb' => [['label' => 'Tableau de bord']],
        ];
        foreach (['subtitle', 'todosTitle', 'todosLink'] as $key) {
            if (isset($options[$key])) {
                $props[$key] = $options[$key];
            }
        }

        return Inertia::render('Dashboard/Index', $props);
    }

    /** URL d'une route si elle existe (liens des indicateurs). */
    protected function routeIfExists(string $name, mixed $params = []): ?string
    {
        return Route::has($name) ? route($name, $params) : null;
    }
}
