@extends('Layouts.app')
@section('top')
@php
    $u = auth()->user();
    $sessionAgence = \Illuminate\Support\Facades\Session::get('agence');
    $agence = $sessionAgence ?? $u?->agence;
    if ($u && ! $sessionAgence) {
        $u->loadMissing(['agence.representation']);
        $agence = $agence ?? $u->agence;
    }
    if ($agence && ! $agence->relationLoaded('representation')) {
        $agence->loadMissing('representation');
    }
    $primaryBadge = $agence
        ? $agence->name.($agence->representation ? ' — '.$agence->representation->name : '')
        : 'Analyste sans rattachement agence';
@endphp
@include('partials.layout-role-header-top', [
    'roleLabel' => 'Analyste financier',
    'primaryBadge' => $primaryBadge,
    'logoutFormId' => 'logout-form-analyste',
])
@endsection
@section('navigation')
 <?php
    $active = \Illuminate\Support\Facades\Session::get('active');
    $r = request()->route()?->getName() ?? '';
?>
     <div class="mainnav__categoriy py-3 mb-4">
        <ul class="mainnav__menu nav flex-column gap-2">
           <li class="nav-item">
               <a href="{{ route('analyste.dashboard') }}" class="nav-link mininav-toggle {{ $active==1?'active':'' }}"><i class="bi bi-house-door fs-4 me-2"></i>
                   <span class="nav-label mininav-content ms-1">Tableau de bord</span>
               </a>
           </li>

           <li class="nav-item">
                <a href="{{ route('analyste.dossiers.index') }}" class="nav-link mininav-toggle {{ $active==2?'active':'' }}"><i class="bi bi-folder2-open fs-5 me-2"></i>
                    <span class="nav-label mininav-content ms-1">Dossiers d'instruction</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="mainnav__categoriy py-3">
    <h6 class="mainnav__caption mt-0 px-3 fw-bold">PORTEFEUILLE</h6>
    <ul class="mainnav__menu nav flex-column">

    <li class="nav-item">
                <a href="{{ route('analyste.programmes.index') }}" class="nav-link mininav-toggle {{ $active==3?'active':'' }}"><i class="bi bi-diagram-3 fs-5 me-2"></i>
                    <span class="nav-label mininav-content ms-1">Programmes</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('analyste.entreprises.index') }}" class="nav-link mininav-toggle {{ $active==4?'active':'' }}"><i class="bi bi-bank fs-5 me-2"></i>
                    <span class="nav-label mininav-content ms-1">Entreprises</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('analyste.entreprises.prospects') }}" class="nav-link mininav-toggle {{ $active==5?'active':'' }}"><i class="bi bi-telephone fs-5 me-2"></i>
                    <span class="nav-label mininav-content ms-1">Prospects</span>
                </a>
            </li>
    </ul>
</div>
@include('partials.layout-tdb-link')
@include('partials.layout-role-nav-compte')
    @yield('modal')
@endsection
