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
 */
trait RendersInertiaDashboard
{
    /**
     * @param  array<int, array{key: string, label: string, icon: string, href?: ?string, tone?: string}>  $kpis
     * @param  Closure(): array  $stats
     * @param  Closure(): array|null  $todos
     */
    protected function renderInertiaDashboard(string $title, array $kpis, Closure $stats, ?Closure $todos = null): Response
    {
        return Inertia::render('Dashboard/Index', [
            'title' => $title,
            'kpis' => $kpis,
            'hasTodos' => $todos !== null,
            'hasInsights' => false,
            'stats' => Inertia::defer($stats),
            'todos' => Inertia::defer($todos ?? fn () => []),
            'insights' => null,
            'breadcrumb' => [['label' => 'Tableau de bord']],
        ]);
    }

    /** URL d'une route si elle existe (liens des indicateurs). */
    protected function routeIfExists(string $name, mixed $params = []): ?string
    {
        return Route::has($name) ? route($name, $params) : null;
    }
}
