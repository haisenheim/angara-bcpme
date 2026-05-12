@php
    /** Entreprise contextuelle ($item ou $entreprise). */
    $ent = $item ?? $entreprise ?? null;
@endphp
@if ($ent && auth()->check())
    <li>
        <a class="dropdown-item" href="{{ route('simulator.index', ['entreprise_id' => $ent->id]) }}">
            <i class="demo-psi-calculator me-2"></i>Simulation de crédit
        </a>
    </li>
@endif
