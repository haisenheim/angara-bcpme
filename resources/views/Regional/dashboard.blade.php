@extends('Layouts.regional')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">Angara</a></li>
       <li class="breadcrumb-item"><a href="#">Tableau de bord</a></li>
       <li class="breadcrumb-item active" aria-current="page">Accueil</li>
    </ol>
 </nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Tableau de bord du Responsable Regional</h5>
        <p class="lead">Hello {{ auth()->user()->name }}, bienvenu sur <span class="text-muted">Angara</span></p>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <small class="text-muted text-uppercase d-block mb-1">Entreprises (région)</small>
                        <div class="fs-4 fw-semibold" id="reg-stat-entreprises"><span class="spinner-border spinner-border-sm" role="status"></span></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <small class="text-muted text-uppercase d-block mb-1">Dossiers</small>
                        <div class="fs-4 fw-semibold" id="reg-stat-dossiers"><span class="spinner-border spinner-border-sm" role="status"></span></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <small class="text-muted text-uppercase d-block mb-1">Prospects soumis</small>
                        <div class="fs-4 fw-semibold" id="reg-stat-prospects"><span class="spinner-border spinner-border-sm" role="status"></span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/simple-dashboard-stats.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            AngaraLoadDashboardStats(@json(route('regional.dashboard.stats')), {
                entreprises: 'reg-stat-entreprises',
                dossiers: 'reg-stat-dossiers',
                prospects: 'reg-stat-prospects',
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
