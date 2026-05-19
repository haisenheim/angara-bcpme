<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Http\RedirectResponse;

trait RedirectsBackToGrilleSousCritere
{
    protected function redirectBackToGrilleSousCritere(int|string|null $sousCritereId = null): RedirectResponse
    {
        $fragment = ($sousCritereId !== null && $sousCritereId !== '')
            ? 'grille-souscritere-'.(int) $sousCritereId
            : 'grille-notation';

        return redirect()->back()->withFragment($fragment);
    }
}
