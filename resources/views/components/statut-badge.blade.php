{{--
    Badge de statut métier réutilisable (entreprise client / dossier d'instruction).

    Usage :
        <x-statut-badge :statut="$entreprise->clientStatutPresentation()" />
        <x-statut-badge :statut="$dossier->instructionStatutPresentation()" :show-detail="false" />

    Format attendu pour $statut :
        ['code' => 'xxx', 'label' => '...', 'badge_variant' => 'success|info|warning|danger|secondary', 'detail' => '...']

    Props :
    - statut       : tableau renvoyé par les méthodes du modèle (cf. ci-dessus)
    - showDetail   : bool — afficher le détail (tooltip + texte) sous le badge (défaut: true)
    - compact      : bool — affichage compact sans détail textuel (défaut: false)
--}}
@props([
    'statut' => null,
    'showDetail' => true,
    'compact' => false,
])
@php
    $statut = is_array($statut) ? $statut : [];
    $code = $statut['code'] ?? 'inconnu';
    $label = $statut['label'] ?? 'Statut inconnu';
    $variant = $statut['badge_variant'] ?? 'secondary';
    $detail = $statut['detail'] ?? null;
    $allowedVariants = ['success', 'info', 'warning', 'danger', 'secondary', 'primary', 'light', 'dark'];
    if (! in_array($variant, $allowedVariants, true)) {
        $variant = 'secondary';
    }
@endphp
<span {{ $attributes->merge(['class' => 'd-inline-flex flex-column align-items-start gap-1']) }} data-statut-code="{{ $code }}">
    <span class="badge bg-{{ $variant }} text-uppercase fw-semibold" @if($detail) title="{{ $detail }}" data-bs-toggle="tooltip" @endif>
        {{ $label }}
    </span>
    @if($showDetail && ! $compact && $detail)
        <span class="small text-body-secondary">{{ $detail }}</span>
    @endif
</span>
