@php
    /** @var array $node */
    /** @var int $depth */
    /** @var bool $canEdit */
    /** @var \App\Models\Entreprise $entreprise */

    $rowClass = match ($node['type'] ?? 'produit') {
        'section' => 'engagement-row-section',
        'rubrique' => 'engagement-row-rubrique',
        'nature' => 'engagement-row-nature',
        default => 'engagement-row-produit',
    };
    $indent = $depth * 18;
    $totalMontant = ($node['encours_actuel'] ?? 0) + ($node['sollicite_montant'] ?? 0);
    $variation = ($node['sollicite_montant'] ?? 0) - ($node['encours_actuel'] ?? 0);
@endphp
<tr class="engagement-grid-row {{ $rowClass }}">
    <td class="engagement-libelle">
        <span style="padding-left: {{ $indent }}px;">{{ $node['libelle'] }}</span>
    </td>
    <td>—</td>
    <td class="text-end">{{ number_format($node['encours_initial'] ?? 0, 0, ',', ' ') }}</td>
    <td class="text-end">{{ number_format($node['encours_actuel'] ?? 0, 0, ',', ' ') }}</td>
    <td class="text-end">{{ number_format($node['encours_remboursement_n1'] ?? 0, 0, ',', ' ') }}</td>
    <td class="text-end">{{ number_format($node['encours_retards'] ?? 0, 0, ',', ' ') }}</td>
    <td class="text-end engagement-impayes">{{ number_format($node['encours_impayes'] ?? 0, 0, ',', ' ') }}</td>
    <td>—</td>
    <td>—</td>
    <td class="text-end">{{ number_format($node['sollicite_montant'] ?? 0, 0, ',', ' ') }}</td>
    <td>—</td>
    <td class="text-end engagement-total">{{ number_format($totalMontant, 0, ',', ' ') }}</td>
    <td class="text-end">{{ number_format($variation, 0, ',', ' ') }}</td>
    <td class="engagement-actions">
        @if (($node['is_leaf'] ?? false) && $canEdit)
            <button type="button" class="btn btn-xs btn-outline-primary"
                    data-bs-toggle="modal" data-bs-target="#engagementLigneModal"
                    data-action="create"
                    data-categorie-id="{{ $node['id'] }}"
                    data-categorie-libelle="{{ $node['libelle'] }}">
                <i class="demo-psi-add"></i>
            </button>
        @endif
    </td>
</tr>

@foreach ($node['lignes'] ?? [] as $ligne)
    @php
        $totalLigne = ($ligne['encours_actuel'] ?? 0) + ($ligne['sollicite_montant'] ?? 0);
        $dEncours = $ligne['encours_date_validite'] ?? null;
        $dSollicite = $ligne['sollicite_date_validite'] ?? null;
    @endphp
    <tr class="engagement-grid-row engagement-row-ligne" data-ligne-id="{{ $ligne['id'] }}">
        <td class="engagement-libelle">
            <span style="padding-left: {{ $indent + 24 }}px;" class="text-muted small">↳ {{ $ligne['partenaire_nom'] ?? '—' }}</span>
        </td>
        <td>
            @if (! empty($ligne['partenaire_kind_label']))
                <span class="badge bg-light text-dark border">{{ $ligne['partenaire_kind_label'] }}</span>
            @else
                —
            @endif
        </td>
        <td class="text-end">{{ number_format($ligne['encours_initial'] ?? 0, 0, ',', ' ') }}</td>
        <td class="text-end">{{ number_format($ligne['encours_actuel'] ?? 0, 0, ',', ' ') }}</td>
        <td class="text-end">{{ number_format($ligne['encours_remboursement_n1'] ?? 0, 0, ',', ' ') }}</td>
        <td class="text-end">{{ number_format($ligne['encours_retards'] ?? 0, 0, ',', ' ') }}</td>
        <td class="text-end engagement-impayes">{{ number_format($ligne['encours_impayes'] ?? 0, 0, ',', ' ') }}</td>
        <td>{{ $ligne['encours_statut_label'] ?? '—' }}</td>
        <td>{{ $dEncours ? \Carbon\Carbon::parse($dEncours)->format('d/m/Y') : '—' }}</td>
        <td class="text-end">{{ number_format($ligne['sollicite_montant'] ?? 0, 0, ',', ' ') }}</td>
        <td>{{ $dSollicite ? \Carbon\Carbon::parse($dSollicite)->format('d/m/Y') : '—' }}</td>
        <td class="text-end engagement-total">{{ number_format($totalLigne, 0, ',', ' ') }}</td>
        <td class="text-end">{{ number_format(($ligne['sollicite_montant'] ?? 0) - ($ligne['encours_actuel'] ?? 0), 0, ',', ' ') }}</td>
        <td class="engagement-actions text-nowrap">
            <button type="button" class="btn btn-xs btn-primary me-1"
                    data-bs-toggle="modal" data-bs-target="#engagementLigneDetailModal"
                    data-ligne="{{ json_encode($ligne) }}"
                    title="Consulter le détail">
                <i class="demo-psi-eye"></i>
            </button>
            @if (! empty($ligne['commentaire']))
                <button type="button" class="btn btn-xs btn-outline-info me-1"
                        data-bs-toggle="modal" data-bs-target="#engagementCommentaireModal"
                        data-commentaire="{{ $ligne['commentaire'] }}"
                        title="Commentaire">
                    <i class="demo-psi-speech-bubble"></i>
                </button>
            @endif
            @if ($canEdit)
                <button type="button" class="btn btn-xs btn-outline-secondary me-1"
                        data-bs-toggle="modal" data-bs-target="#engagementLigneModal"
                        data-action="edit"
                        data-ligne="{{ json_encode($ligne) }}">
                    <i class="demo-psi-pen-5"></i>
                </button>
                <form method="post"
                      action="{{ route('engagements.lignes.destroy', ['token' => $entreprise->token, 'ligne' => $ligne['id']]) }}"
                      class="d-inline"
                      onsubmit="return confirm('Supprimer cette ligne ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-xs btn-outline-danger">
                        <i class="demo-psi-trash"></i>
                    </button>
                </form>
            @endif
        </td>
    </tr>
@endforeach

@foreach ($node['children'] ?? [] as $child)
    @include('engagements.partials.grid-node', [
        'node' => $child,
        'depth' => $depth + 1,
        'canEdit' => $canEdit,
        'entreprise' => $entreprise,
    ])
@endforeach
