{{-- Badge + libellé court pour l’état structuration client (EER). Variables : $eer (DossierEntreeRelation|null) --}}
@php($__csp = \App\Models\DossierEntreeRelation::clientStructurationPresentation($eer ?? null))
<span class="badge rounded-pill text-bg-{{ $__csp['badge_variant'] }}" @if(!empty($__csp['detail'])) title="{{ e($__csp['detail']) }}" @endif>{{ $__csp['label'] }}</span>
@if(!empty($showNonStructureHint) && $eer && $eer->isClientStructurationNonStructure())
    <span class="badge rounded-pill bg-light text-dark border ms-1" title="Regroupe en cours, en attente ou rejetée tant que la structuration n’est pas validée par le chef d’agence.">Non structuré</span>
@endif
