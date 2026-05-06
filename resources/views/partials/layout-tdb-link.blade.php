{{-- Lien vers le tableau de bord centralisé (5 familles d'indicateurs - prompt l. 86-105). --}}
<div class="mainnav__categoriy py-3">
    <h6 class="mainnav__caption mt-0 px-3 fw-bold">ANALYSE</h6>
    <ul class="mainnav__menu nav flex-column">
        <li class="nav-item">
            <a href="{{ route('tdb.index') }}"
               class="nav-link mininav-toggle {{ str_starts_with(request()->route()?->getName() ?? '', 'tdb.') ? 'active' : '' }}">
                <i class="bi bi-bar-chart-line fs-5 me-2"></i>
                <span class="nav-label mininav-content ms-1">Tableaux de bord</span>
            </a>
        </li>
    </ul>
</div>
