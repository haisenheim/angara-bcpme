@extends('Layouts.gestionnaire')

@section('title', 'Grille d\'analyse critique')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('gestionnaire.dashboard') }}">ANGARA</a></li>
        <li class="breadcrumb-item"><a href="{{ route('gestionnaire.dossiers.index') }}">Dossiers</a></li>
        <li class="breadcrumb-item"><a href="{{ route('gestionnaire.dossiers.show', $item->token) }}">{{ $item->entreprise?->name ?? 'Dossier' }}</a></li>
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
        <li><a data-sequence="8" class="dropdown-item" data-bs-target="#report1Modal" data-bs-toggle="modal" href="#">Conclusions motivées, recommandations du gestionnaire</a></li>
    </ul>
</div>
@endsection

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    @endif

    <div class="container">
        <div class="d-flex justify-content-center">
            <div style="width:800px" class="card">
                <div class="card-header p-4">
                    <h4 class="text-center mb-0">GRILLE D'ANALYSE CRITIQUE</h4>
                </div>
                <div class="card-body table-responsive">
                    <div role="tabpanel">
                        <div class="mt-1 border rounded rounded-2 p-2">
                            <h4 class="fs-5">1. INFORMATIONS GENERALES</h4>
                            <p class="lh-base"><?= $item['donnees_generales'] ?? '—' ?></p>
                        </div>
                        <div class="mt-4 border rounded rounded-2 p-2">
                            <h4 class="fs-5">2. ANALYSE D'ENSEMBLE</h4>
                            <p class="lh-base"><?= $item['analyse_ensemble'] ?? '—' ?></p>
                        </div>
                        <div class="mt-4 border rounded rounded-2 p-2">
                            <h4 class="fs-5">3. ANALYSE FINANCIERE</h4>
                            <p class="lh-base"><?= $item['analyse_financiere'] ?? '—' ?></p>
                        </div>
                        <div class="mt-4 border rounded rounded-2 p-2">
                            <h4 class="fs-5">4. APPUIS FINANCIERS ET NON FINANCIERS</h4>
                            <p class="lh-base"><?= $item['appuis'] ?? '—' ?></p>
                        </div>
                        <div class="mt-2 border rounded rounded-2 p-2">
                            <h4 class="fs-5">5. ANALYSE DU RISQUE ET DE LA CAPACITE DE REMBOURSEMENT</h4>
                            <p class="lh-base"><?= $item['analyse_risque'] ?? '—' ?></p>
                        </div>
                        <div class="mt-4 border rounded rounded-2 p-2">
                            <h4 class="fs-5">6. RENTABILITE DE LA RELATION POUR L'ETABLISSEMENT</h4>
                            <p class="lh-base"><?= $item['analyse_rentabilite'] ?? '—' ?></p>
                        </div>
                        <div class="mt-4 border rounded rounded-2 p-2">
                            <h4 class="fs-5">7. CONCLUSIONS MOTIVEES, RECOMMANDATIONS DE L'ANALYSTE FINANCIER</h4>
                            <p class="lh-base"><?= $item['conclusions_analyste'] ?? '—' ?></p>
                        </div>
                        <div class="mt-4 border rounded rounded-2 p-2 border-primary border-2">
                            <h3 class="fs-5">8. CONCLUSIONS ET RECOMMANDATIONS DU GESTIONNAIRE</h3>
                            <p class="lh-base"><?= $item['conclusions_gestionnaire'] ?? '—' ?></p>
                        </div>
                        <div class="mt-4 border rounded rounded-2 p-2 border-primary border-2">
                            <h4 class="fs-5">9. REMARQUES ET RECOMMANDATIONS DU CHEF D'AGENCE</h4>
                            <p class="lh-base"><?= $item['conclusions_ca'] ?? '—' ?></p>
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
                    <h5 class="modal-title">Conclusions et recommandations du gestionnaire</h5>
                    <button data-bs-dismiss="modal" class="btn btn-sm">×</button>
                </div>
                <div class="modal-body">
                    <p id="description" class="text-muted small">Saisissez vos conclusions motivées et recommandations pour compléter la grille d'analyse critique.</p>
                    <form action="{{ route('gestionnaire.dossier.set.analyse') }}" method="post">
                        @csrf
                        <input type="hidden" value="{{ $item['id'] }}" name="dossier_id">
                        <input type="hidden" id="sequence" name="sequence" value="8">
                        <div class="mt-2">
                            <div id="quill-editor-grille" class="mb-3" style="height: 150px;"></div>
                            <textarea rows="3" class="d-none" name="content" id="quill-editor-area-grille">{{ $item->conclusions_gestionnaire ?? '' }}</textarea>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">ENREGISTRER</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var editor = new Quill('#quill-editor-grille', {
                theme: 'snow',
                modules: { toolbar: [ [{ 'header': [1, 2, false] }], ['bold', 'italic', 'underline', 'strike'], ['blockquote', 'code-block'], [{ 'list': 'ordered' }, { 'list': 'bullet' }], ['link'], ['clean'] ] }
            });
            var quillArea = document.getElementById('quill-editor-area-grille');
            editor.root.innerHTML = quillArea.value || '';
            editor.on('text-change', function() { quillArea.value = editor.root.innerHTML; });

            document.getElementById('report1Modal').addEventListener('show.bs.modal', function() {
                editor.root.innerHTML = quillArea.value || '';
            });
        });
        $('.analyse .dropdown-item').click(function(){
            var seq = $(this).data('sequence');
            $('#sequence').val(seq);
        });
    </script>
@endsection
