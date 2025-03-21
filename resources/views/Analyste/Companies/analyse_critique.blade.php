@extends('Layouts.analyste')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">ANGARA</a></li>
       <li class="breadcrumb-item"><a href="{{ route('analyste.entreprises.show',$entreprise->token) }}">{{ $entreprise->name }}</a></li>
       <li class="breadcrumb-item active" aria-current="page">Grille d'analyse critique</li>
    </ol>
 </nav>
@endsection

@section('actions')
<div class="btn-group">
    <button type="button" class="btn btn-xs btn-outline-primary dropdown-toggle hstack gap-2" data-bs-toggle="dropdown" aria-expanded="false">
    Actions
    <span class="vr"></span>
    </button>
    <ul class="dropdown-menu analyse">
        <li><a data-sequence="1" class="dropdown-item" data-bs-target="#report1Modal" data-bs-toggle="modal" href="#">Brèves données générales actualisées sur l'emprunteur</a></li>
        <li><a data-sequence="2" class="dropdown-item" data-bs-target="#report1Modal" data-bs-toggle="modal" href="#">Analyse critique d'ensemble</a></li>
        <li><a data-sequence="3" class="dropdown-item" data-bs-target="#report1Modal" data-bs-toggle="modal" href="#">Analyse financière de l'emprunteur</a></li>
        <li><a data-sequence="4" class="dropdown-item" data-bs-target="#report1Modal" data-bs-toggle="modal" href="#">Appuis financiers et non-financiers proposés</a></li>
        <li><a data-sequence="5" class="dropdown-item" data-bs-target="#report1Modal" data-bs-toggle="modal" href="#">Analyse du risque et de la capacité de remboursement de l'emprunteur</a></li>
        <li><a data-sequence="6" class="dropdown-item" data-bs-target="#report1Modal" data-bs-toggle="modal" href="#">Rentabilité de la relation pour l’établissement</a></li>
        <li><a data-sequence="7" class="dropdown-item" data-bs-target="#report1Modal" data-bs-toggle="modal" href="#">Conclusions motivées, recommandations de l’Analyste Financier</a></li>
    </ul>
</div>
@endsection


@section('content')
    <div class="container">
        <div class="d-flex justify-content-center">
            <div style="max-width:1000px" class="card">
                <div class="card-header p-4">
                    <h4 class="text-center mb-0">GRILLE D'ANALYSE CRITIQUE</h4>
                </div>
                <div class="card-body table-responsive">

                </div>
            </div>
        </div>
    </div>


    <style>
        .table th{
            border: var(--bs-border-width) var(--bs-border-style) #555 !important;
            font-weight: 900;
        }

        .table td{
            border: var(--bs-border-width) var(--bs-border-style) #888 !important;
            font-weight: normal;
        }

        th.vertical-align{
            display: table-cell;
            vertical-align: middle;
            text-align: center;
        }
    </style>

@endsection


