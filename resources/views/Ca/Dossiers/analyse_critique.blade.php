@extends('Layouts.ca')

@section('title', 'Grille d\'analyse critique')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('ca.dashboard') }}">Angara</a></li>
        <li class="breadcrumb-item"><a href="{{ route('ca.dossiers.index') }}">Dossiers</a></li>
        <li class="breadcrumb-item"><a href="{{ route('ca.dossiers.show', $item->token) }}">{{ $item->entreprise?->name ?? 'Dossier' }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">Grille d'analyse critique</li>
    </ol>
</nav>
@endsection

@section('actions')
<div class="dropdown">
    <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="demo-psi-dot-vertical me-1"></i> Actions
    </button>
    <ul class="dropdown-menu dropdown-menu-end">
        <li><a class="dropdown-item" href="{{ route('ca.entreprise.get.engagements', $item->entreprise?->token) }}"><i class="demo-psi-file-text-image me-2"></i>État des engagements</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item" data-sequence="9" data-bs-target="#reportCaModal" data-bs-toggle="modal" href="#"><i class="demo-psi-pen-5 me-2"></i>Saisir remarques et recommandations</a></li>
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
            <div style="width:800px" class="card border-0 shadow-sm">
                <div class="card-header p-4 bg-transparent">
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
                            <h4 class="fs-5">8. CONCLUSIONS ET RECOMMANDATIONS DU GESTIONNAIRE</h4>
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

    {{-- Modal Remarques et recommandations du Chef d'agence --}}
    <div class="modal fade" id="reportCaModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Remarques et recommandations du Chef d'agence</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small mb-3">Saisissez vos remarques et recommandations pour compléter la grille d'analyse critique (point 9).</p>
                    <form action="{{ route('ca.dossier.set.analyse') }}" method="post">
                        @csrf
                        <input type="hidden" name="dossier_id" value="{{ $item->id }}">
                        <input type="hidden" name="sequence" value="9">
                        <div class="mb-3">
                            <div id="quill-editor-ca" class="mb-3" style="height: 150px;"></div>
                            <textarea rows="3" class="d-none" name="content" id="quill-editor-area-ca">{{ $item->conclusions_ca ?? '' }}</textarea>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('quill-editor-ca')) {
        var editor = new Quill('#quill-editor-ca', {
            theme: 'snow',
            modules: { toolbar: [ [{ 'header': [1, 2, false] }], ['bold', 'italic', 'underline', 'strike'], ['blockquote', 'code-block'], [{ 'list': 'ordered' }, { 'list': 'bullet' }], ['link'], ['clean'] ] }
        });
        var quillArea = document.getElementById('quill-editor-area-ca');
        editor.root.innerHTML = quillArea.value || '';
        editor.on('text-change', function() { quillArea.value = editor.root.innerHTML; });
        document.getElementById('reportCaModal').addEventListener('show.bs.modal', function() {
            editor.root.innerHTML = quillArea.value || '';
        });
    }
});
</script>
@endsection
