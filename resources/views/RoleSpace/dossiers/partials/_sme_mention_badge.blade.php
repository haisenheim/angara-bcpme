@php
    $mentionValue = trim((string) ($mention ?? $smeMention ?? ($sme->mention ?? $sme['mention'] ?? '')));
    $mentionBadgeClass = match (true) {
        str_contains(mb_strtolower($mentionValue), 'excellent') => 'bg-success',
        str_contains(mb_strtolower($mentionValue), 'très bon') || str_contains(mb_strtolower($mentionValue), 'tres bon') => 'bg-info',
        $mentionValue === 'Bon' || str_contains(mb_strtolower($mentionValue), 'assez bon') => 'bg-primary',
        str_contains(mb_strtolower($mentionValue), 'moyen') => 'bg-warning text-dark',
        str_contains(mb_strtolower($mentionValue), 'acceptable') => 'bg-secondary',
        str_contains(mb_strtolower($mentionValue), 'vulnérable') || str_contains(mb_strtolower($mentionValue), 'vulnerable') => 'bg-danger',
        str_contains(mb_strtolower($mentionValue), 'douteux') => 'bg-dark',
        default => 'bg-primary',
    };
@endphp
@if($mentionValue !== '')
    <span class="badge rounded-pill {{ $mentionBadgeClass }} px-3 py-2 fs-6 fw-semibold">{{ $mentionValue }}</span>
@endif
