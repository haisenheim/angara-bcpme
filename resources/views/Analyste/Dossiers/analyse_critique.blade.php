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




@section('content')
    <div class="container">
        <div class="d-flex justify-content-center">
            <div style="max-width:1000px" class="card">
                <div class="card-header p-4">
                    <h4 class="text-center mb-0">GRILLE D'ANALYSE CRITIQUE</h4>
                </div>
                <div class="card-body table-responsive">
                    <div id="_dm-coTabsBaseProfile" class="tab-pane fade" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="mt-2 border rounded rounded-2 p-2">
                            <h4 class="fs-6">1. INFORMATIONS GENERALES</h4>
                            <p class="lh-base"><?= $item['donneesGenerales'] ?></p>
                        </div>
                        <div class="mt-2 border rounded rounded-2 p-2">
                            <h4 class="fs-6">2. ANALYSE D'ENSEMBLE</h4>
                            <p class="lh-base"><?= $item['analyseEnsemble'] ?></p>
                        </div>
                        <div class="mt-2 border rounded rounded-2 p-2">
                            <h4 class="fs-6">3. ANALYSE FINANCIERE</h4>
                            <p class="lh-base"><?= $item['analyseFinanciere'] ?></p>
                        </div>
                        <div class="mt-2 border rounded rounded-2 p-2">
                            <h4 class="fs-6">4. APPUIS FINANCIERS ET NON FINANCIERS</h4>
                            <p class="lh-base"><?= $item['appuis'] ?></p>
                        </div>
                        <div class="mt-2 border rounded rounded-2 p-2">
                            <h4 class="fs-6">5. ANALYSE DU RISQUE ET DE LA CAPACITE DE REMBOURSEMENT</h4>
                            <p class="lh-base"><?= $item['analyseRisque'] ?></p>
                        </div>
                        <div class="mt-2 border rounded rounded-2 p-2">
                            <h4 class="fs-6">6. RENTABILITE DE LA RELATION POUR L'ETABILISSEMENT</h4>
                            <p class="lh-base"><?= $item['analyseRentabilite'] ?></p>
                        </div>
                        <div class="mt-2 border rounded rounded-2 p-2">
                            <h4 class="fs-6">7. CONCLUSIONS GENERALES POUR L'ANALYSTE</h4>
                            <p class="lh-base"><?= $item['conclusionsAnalyste'] ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="report1Modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header justify-content-between">
                    <div style="float: right">
                        <button data-bs-dismiss="modal" class="btn btn-sm" >x</button>
                    </div>
                </div>
                <div class="modal-body">
                    <p id="description"></p>
                    <form action="{{ route('analyste.dossier.set.analyse') }}" method="post">
                        @csrf
                        <input type="hidden" value="{{ $item['id'] }}" name="dossier_id">
                        <input type="hidden" id="sequence" name="sequence">
                        <div class="mt-2">
                            <x-quill :name="'content'"></x-quill>
                        </div>
                        <div class="mt-1">
                            <button type="submit" class="btn-primary btn">ENREGISTRER</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection


