{{-- En-tête header (badges contexte + rôle + menu utilisateur), aligné sur le layout gestionnaire / respexp. --}}
@php
    $logoutFormId = $logoutFormId ?? 'logout-form-role-header';
    $sessionUser = \Illuminate\Support\Facades\Session::get('user');
    $u = auth()->user();

    if (! isset($primaryBadge)) {
        $agence = \Illuminate\Support\Facades\Session::get('agence') ?? $u?->agence;
        if ($u && ! \Illuminate\Support\Facades\Session::has('agence')) {
            $u->loadMissing(['agence.representation']);
            $agence = $agence ?? $u->agence;
        }
        if ($agence && ! $agence->relationLoaded('representation')) {
            $agence->loadMissing('representation');
        }
        if ($agence) {
            $primaryBadge = $agence->name.($agence->representation ? ' — '.$agence->representation->name : '');
        } else {
            $primaryBadge = 'BC-PME';
        }
    }

    $avatar = $sessionUser?->photo ?? $u?->photo;
@endphp
<div>
    <div class="d-flex flex-wrap gap-3 align-items-center">
        <span class="badge bg-white text-dark fs-6">{{ $primaryBadge }}</span>
        <span class="text-white-50 d-none d-sm-inline">|</span>
        <strong><span class="badge bg-white text-dark fs-6">{{ $roleLabel }}</span></strong>
    </div>
</div>
<div class="header__content-end">
    <div class="dropdown">
        <button class="btn btn-icon btn-sm" type="button" data-bs-toggle="dropdown" aria-label="Menu utilisateur" aria-expanded="false">
            <img class="mainnav__avatar img-sm rounded-circle border" src="{{ $avatar }}" alt="">
        </button>
        <div class="dropdown-menu dropdown-menu-end">
            <div class="d-flex align-items-center border-bottom pb-3 px-3 pt-2">
                <div class="flex-grow-1">
                    <h3 class="h6 mb-1">{{ $u?->name }}</h3>
                    <span class="text-body-secondary small">{{ $u?->email }}</span>
                </div>
            </div>
            <a class="dropdown-item p-3" href="{{ route('profile') }}">Mon profil</a>
            <form id="{{ $logoutFormId }}" method="POST" action="{{ route('logout') }}">
                @csrf
                <a role="button" class="dropdown-item p-3" onclick="document.getElementById('{{ $logoutFormId }}').submit();">Se déconnecter</a>
            </form>
        </div>
    </div>
</div>
