@php
    /** @var array $tree */
    /** @var bool $canEdit */
    /** @var \App\Models\Entreprise $entreprise */
@endphp
<table class="table table-sm table-bordered align-middle mb-0 engagement-grid-table">
    <thead class="engagement-grid-head">
        <tr>
            <th rowspan="2" class="engagement-th-libelle">Engagement</th>
            <th rowspan="2">Partenaire</th>
            <th colspan="7" class="text-center">Encours</th>
            <th colspan="2" class="text-center">Sollicité</th>
            <th colspan="2" class="text-center">Total</th>
            <th rowspan="2" class="text-center">Actions</th>
        </tr>
        <tr>
            <th class="text-end">Initial</th>
            <th class="text-end">Actuel</th>
            <th class="text-end">Remb. N-1</th>
            <th class="text-end">Retards</th>
            <th class="text-end">Impayés</th>
            <th>Statut</th>
            <th>Date validité</th>
            <th class="text-end">Montant</th>
            <th>Date validité</th>
            <th class="text-end">Montant</th>
            <th class="text-end">Variation</th>
        </tr>
    </thead>
    <tbody>
        @if (empty($tree))
            <tr><td colspan="14" class="text-center text-muted py-4">Aucun engagement à afficher.</td></tr>
        @else
            @foreach ($tree as $root)
                @include('engagements.partials.grid-node', [
                    'node' => $root,
                    'depth' => 0,
                    'canEdit' => $canEdit,
                    'entreprise' => $entreprise,
                ])
            @endforeach
        @endif
    </tbody>
</table>
