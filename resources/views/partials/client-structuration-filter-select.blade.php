{{-- Filtre statut structuration client (EER). Variables : $selected (valeur courante), optionnel : $name, $id, $classes, $label --}}
@php
    $selected = $selected ?? null;
    $name = $name ?? 'client_structuration_status';
    $id = $id ?? 'filter-client-structuration';
    $classes = $classes ?? 'form-select form-select-sm';
    $label = $label ?? 'Statut structuration';
@endphp
<label for="{{ $id }}" class="form-label small text-muted mb-1">{{ $label }}</label>
<select name="{{ $name }}" id="{{ $id }}" class="{{ $classes }}">
    <option value="" @selected($selected === null || $selected === '')>Tous</option>
    @foreach(\App\Models\DossierEntreeRelation::clientStructurationStatusFilterLabels() as $val => $lab)
        <option value="{{ $val }}" @selected((string) ($selected ?? '') === (string) $val)>{{ $lab }}</option>
    @endforeach
</select>
