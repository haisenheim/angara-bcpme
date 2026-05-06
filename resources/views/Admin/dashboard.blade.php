@extends('Layouts.admin')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">Cogelo</a></li>
       <li class="breadcrumb-item"><a href="#">Tableau de bord</a></li>
       <li class="breadcrumb-item active" aria-current="page">Accueil</li>
    </ol>
 </nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Tableau de bord SuperAdmin</h5>
        <p class="lead">Hello {{ auth()->user()->name }}, bienvenu sur <span class="text-muted">Cogelo</span> votre espace de supervision globale.</p>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <small class="text-muted text-uppercase d-block mb-1">Utilisateurs</small>
                        <div class="fs-4 fw-semibold" id="adm-stat-users"><span class="spinner-border spinner-border-sm" role="status"></span></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <small class="text-muted text-uppercase d-block mb-1">Entreprises</small>
                        <div class="fs-4 fw-semibold" id="adm-stat-entreprises"><span class="spinner-border spinner-border-sm" role="status"></span></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <small class="text-muted text-uppercase d-block mb-1">Dossiers d'instruction</small>
                        <div class="fs-4 fw-semibold" id="adm-stat-dossiers"><span class="spinner-border spinner-border-sm" role="status"></span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/simple-dashboard-stats.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            AngaraLoadDashboardStats(@json(route('admin.dashboard.stats')), {
                users: 'adm-stat-users',
                entreprises: 'adm-stat-entreprises',
                dossiers: 'adm-stat-dossiers',
            });
        });
    </script>
    <script src="{{ asset('assets/vendors/chart.js/chart.umd.min.js') }}"></script>
    <style>
        .text-bold{
            font-weight: 800;
        }
    </style>
@endsection
