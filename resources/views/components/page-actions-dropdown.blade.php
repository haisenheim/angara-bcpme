@props([
    'buttonId' => null,
    'menuClass' => 'dropdown-menu dropdown-menu-end border shadow-sm py-2',
    'menuStyle' => null,
])

@php
    $toggleId = $buttonId ?? 'pageActionsDropdown';
@endphp

<div {{ $attributes->class(['dropdown angara-page-actions-dropdown']) }}>
    <button
        type="button"
        class="btn btn-sm btn-angara-actions dropdown-toggle"
        id="{{ $toggleId }}"
        data-bs-toggle="dropdown"
        data-bs-auto-close="true"
        aria-expanded="false"
        aria-haspopup="true"
        aria-label="Menu Actions"
        title="Actions"
    ><i class="demo-psi-dot-vertical me-1" aria-hidden="true"></i>Actions</button>
    <ul class="{{ $menuClass }}" aria-labelledby="{{ $toggleId }}"@if(filled($menuStyle)) style="{{ $menuStyle }}"@endif>
        {{ $slot }}
    </ul>
</div>
