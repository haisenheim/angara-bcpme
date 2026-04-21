{{-- Bloc navigation « Mon profil » (harmonisé respexp / gestionnaire). --}}
@php
    $r = $r ?? (request()->route()?->getName() ?? '');
@endphp
<div class="mainnav__categoriy py-3">
    <h6 class="mainnav__caption mt-0 px-3 fw-bold">COMPTE</h6>
    <ul class="mainnav__menu nav flex-column">
        <li class="nav-item">
            <a href="{{ route('profile') }}" class="nav-link mininav-toggle {{ $r === 'profile' ? 'active' : '' }}"><i class="demo-pli-male fs-3 me-2"></i>
                <span class="nav-label mininav-content ms-1">Mon profil</span>
            </a>
        </li>
    </ul>
</div>
