@extends('Layouts.ca')

@section('title', 'Dossier - ' . ($item->entreprise?->name ?? 'Instruction'))
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('ca.dashboard') }}">Angara</a></li>
        <li class="breadcrumb-item"><a href="{{ route('ca.dossiers.index') }}">Dossiers d'instruction</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $item->entreprise?->name ?? $item->name ?? 'Dossier' }}</li>
    </ol>
</nav>
@endsection

@section('actions')
    <div class="dropdown">
        <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Actions">
            <i class="demo-psi-dot-vertical me-1"></i> Actions
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="{{ route('ca.dossier.get.grille.analyse', $item->token) }}"><i class="demo-psi-magnifi-glass me-2"></i>Grille d'analyse critique</a></li>
            @if($item->entreprise)
            <li><a class="dropdown-item" href="{{ route('ca.entreprise.get.engagements', $item->entreprise->token) }}"><i class="demo-psi-file-text-image me-2"></i>État des engagements</a></li>
            @endif
            <li><hr class="dropdown-divider"></li>
            <li><a data-sequence="8" class="dropdown-item" data-bs-target="#reportCaModal" data-bs-toggle="modal" href="#"><i class="demo-psi-pen-5 me-2"></i>Saisir remarques et recommandations</a></li>
        </ul>
    </div>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0">Dossier d'instruction</h5>
        <p class="text-body-secondary mb-0 mt-1">{{ $item->entreprise?->name }} — {{ $item->programme?->name }}</p>
    </div>
@endsection

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    @endif

    {{-- Cartes récapitulatives --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body py-3">
                    <small class="text-muted text-uppercase d-block mb-1">Entreprise</small>
                    <p class="mb-0 fw-semibold">{{ $item->entreprise?->name ?? '—' }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body py-3">
                    <small class="text-muted text-uppercase d-block mb-1">Programme</small>
                    <p class="mb-0 fw-semibold">{{ $item->programme?->name ?? '—' }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body py-3 text-center">
                    <small class="text-muted text-uppercase d-block mb-1">Note</small>
                    <p class="mb-0 fw-bold fs-5">{{ $item->note ?? $item['note'] ?? '—' }}</p>
                </div>
            </div>
        </div>
        @if($sme ?? null)
            <div class="col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm border-start border-3 border-primary h-100">
                    <div class="card-body py-3">
                        <small class="text-muted text-uppercase d-block mb-1">Notation PME</small>
                        <p class="mb-0 fw-semibold">{{ $sme->name ?? $sme['name'] ?? '—' }}</p>
                        @if(isset($sme->mention) || isset($sme['mention']))
                            <small class="text-muted">{{ $sme->mention ?? $sme['mention'] }}</small>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="row g-3">
        {{-- Colonne gauche : Résumé et notation PME --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 py-3">
                    <h6 class="mb-0 fw-semibold"><i class="demo-psi-information me-2 text-primary"></i>Résumé du dossier</h6>
                </div>
                <div class="card-body">
                    <dl class="row mb-0 g-2">
                        <dt class="col-sm-5 text-muted small">Entreprise</dt>
                        <dd class="col-sm-7">{{ $item->entreprise?->name ?? '—' }}</dd>
                        <dt class="col-sm-5 text-muted small">Programme</dt>
                        <dd class="col-sm-7">{{ $item->programme?->name ?? '—' }}</dd>
                    </dl>
                </div>
            </div>

            @if($sme ?? null)
                <div class="card border-0 shadow-sm mt-3">
                    <div class="card-header bg-transparent border-0 py-3">
                        <h6 class="mb-0 fw-semibold"><i class="demo-psi-bar-chart me-2 text-primary"></i>Notation PME</h6>
                    </div>
                    <div class="card-body">
                        <h6 class="fw-semibold">{{ $sme->name ?? $sme['name'] }}</h6>
                        @if(isset($sme->mention) || isset($sme['mention']))
                            <p class="text-muted small mb-2">{{ $sme->mention ?? $sme['mention'] }}</p>
                        @endif
                        @if(isset($sme->description) || isset($sme['description']))
                            <p class="mb-0 small">{{ $sme->description ?? $sme['description'] }}</p>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        {{-- Colonne droite : Grille de notation --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 py-3">
                    <h6 class="mb-0 fw-semibold"><i class="demo-psi-bar-chart me-2 text-primary"></i>Grille de notation</h6>
                </div>
                <div class="card-body overflow-auto" style="max-height: 70vh;">
                    @if(count($indicateurs ?? []))
                        <div class="table-responsive">
                            <table class="table table-sm table-hover table-notation align-middle">
                                <thead class="table-light sticky-top">
                                    <tr>
                                        <th>Critère principal</th>
                                        <th>N°</th>
                                        <th>%</th>
                                        <th>Sous-critère</th>
                                        <th>Valeur</th>
                                        <th>Note</th>
                                        <th>Pondérée</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($criteres[0]))
                                        <tr>
                                            <th class="vertical-align bg-white" rowspan="{{ count($criteres[0]['souscriteres'] ?? []) + 1 }}">{{ $criteres[0]['name'] }}</th>
                                        </tr>
                                        @foreach($criteres[0]['souscriteres'] ?? [] as $sc)
                                            <tr>
                                                <td>{{ $sc['sequence'] ?? '-' }}</td>
                                                <td>{{ $sc['default'] ?? 0 }}%</td>
                                                <td>{{ $sc['name'] ?? '-' }}</td>
                                                <td>
                                                    {{ isset($sc['reponse']['choice']) ? $sc['reponse']['choice']['valeur'] : '-' }}
                                                    <button type="button" data-bs-toggle="modal" data-bs-target="#critereModal" data-name="{{ $sc['name'] ?? '' }}" data-dossier_id="{{ $item->id }}" data-programme_id="{{ $item->programme_id }}" data-id="{{ $sc['id'] ?? '' }}" class="btn btn-sm btn-link p-0 ms-1 btn-critere" title="Modifier"><i class="demo-psi-pen-5"></i></button>
                                                </td>
                                                <td>{{ isset($sc['reponse']['choice']) ? $sc['reponse']['note'] : '-' }}</td>
                                                <td>{{ isset($sc['reponse']['choice']) ? round($sc['reponse']['note'] * ($sc['default'] ?? 0) / 100, 2) : '-' }}</td>
                                            </tr>
                                        @endforeach
                                        <tr class="table-light">
                                            <td colspan="4"></td>
                                            <th colspan="2">Note critère pondérée</th>
                                            <th>{{ $criteres[0]['note'] ?? 0 }}</th>
                                        </tr>
                                    @endif

                                    @if(isset($criteres[1]))
                                        <tr>
                                            <th class="vertical-align bg-white" rowspan="{{ count($criteres[1]['souscriteres'] ?? []) + 1 }}">{{ $criteres[1]['name'] }}</th>
                                        </tr>
                                        @foreach($criteres[1]['souscriteres'] ?? [] as $sc)
                                            <tr>
                                                <td>{{ $sc['sequence'] ?? '-' }}</td>
                                                <td>{{ $sc['default'] ?? 0 }}%</td>
                                                <td>{{ $sc['name'] ?? '-' }}</td>
                                                <td>
                                                    {{ isset($sc['reponse']['choice']) ? $sc['reponse']['choice']['valeur'] : '-' }}
                                                    <button type="button" data-bs-toggle="modal" data-bs-target="#critereModal" data-name="{{ $sc['name'] ?? '' }}" data-dossier_id="{{ $item->id }}" data-programme_id="{{ $item->programme_id }}" data-id="{{ $sc['id'] ?? '' }}" class="btn btn-sm btn-link p-0 ms-1 btn-critere" title="Modifier"><i class="demo-psi-pen-5"></i></button>
                                                </td>
                                                <td>{{ isset($sc['reponse']['choice']) ? $sc['reponse']['note'] : '-' }}</td>
                                                <td>{{ isset($sc['reponse']['choice']) ? round($sc['reponse']['note'] * ($sc['default'] ?? 0) / 100, 2) : '-' }}</td>
                                            </tr>
                                        @endforeach
                                        <tr class="table-light">
                                            <td colspan="4"></td>
                                            <th colspan="2">Note critère pondérée</th>
                                            <th>{{ $criteres[1]['note'] ?? 0 }}</th>
                                        </tr>
                                    @endif

                                    @if(isset($indicateurs[0]['notation']['details']))
                                        <tr>
                                            <th class="vertical-align bg-white" rowspan="{{ count($indicateurs[0]['notation']['details']) + 1 }}">Finance</th>
                                        </tr>
                                        @foreach($indicateurs[0]['notation']['details'] as $sc)
                                            <tr>
                                                <td>{{ $sc['sequence'] ?? '-' }}</td>
                                                <td>{{ $sc['pourcentage'] ?? 0 }}%</td>
                                                <td>{{ $sc['critere'] ?? '-' }}</td>
                                                <td>{{ $sc['valeur'] ?? '-' }}</td>
                                                <td>{{ $sc['note'] ?? '-' }}</td>
                                                <td>{{ $sc['pondere'] ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                        <tr class="table-light">
                                            <td colspan="4"></td>
                                            <th colspan="2">Note critère pondérée</th>
                                            <th>{{ $indicateurs[0]['notation']['note'] ?? 0 }}</th>
                                        </tr>
                                    @endif

                                    @if(isset($criteres[3]))
                                        <tr>
                                            <th class="vertical-align bg-white" rowspan="{{ count($criteres[3]['souscriteres'] ?? []) + 1 }}">{{ $criteres[3]['name'] }}</th>
                                        </tr>
                                        @foreach($criteres[3]['souscriteres'] ?? [] as $sc)
                                            <tr>
                                                <td>{{ $sc['sequence'] ?? '-' }}</td>
                                                <td>{{ $sc['default'] ?? 0 }}%</td>
                                                <td>{{ $sc['name'] ?? '-' }}</td>
                                                <td>
                                                    {{ isset($sc['reponse']['choice']) ? $sc['reponse']['choice']['valeur'] : '-' }}
                                                    <button type="button" data-bs-toggle="modal" data-bs-target="#critereModal" data-name="{{ $sc['name'] ?? '' }}" data-dossier_id="{{ $item->id }}" data-programme_id="{{ $item->programme_id }}" data-id="{{ $sc['id'] ?? '' }}" class="btn btn-sm btn-link p-0 ms-1 btn-critere" title="Modifier"><i class="demo-psi-pen-5"></i></button>
                                                </td>
                                                <td>{{ isset($sc['reponse']['choice']) ? $sc['reponse']['note'] : '-' }}</td>
                                                <td>{{ isset($sc['reponse']['choice']) ? round($sc['reponse']['note'] * ($sc['default'] ?? 0) / 100, 2) : '-' }}</td>
                                            </tr>
                                        @endforeach
                                        <tr class="table-light">
                                            <td colspan="4"></td>
                                            <th colspan="2">Note critère pondérée</th>
                                            <th>{{ $criteres[3]['note'] ?? 0 }}</th>
                                        </tr>
                                    @endif

                                    <tr class="table-dark">
                                        <th colspan="4"></th>
                                        <th colspan="2">Note pondérée finale</th>
                                        <th colspan="2">Notation PME</th>
                                    </tr>
                                    <tr class="table-dark">
                                        <th colspan="4"></th>
                                        <th class="fw-bold" colspan="2">{{ $item->note ?? $item['note'] ?? '—' }}</th>
                                        <th class="fw-bold" colspan="2">{{ $sme->name ?? $sme['name'] ?? '—' }}</th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="demo-psi-file-search text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-3 mb-2">Aucune donnée DSF importée</p>
                            <p class="small text-muted">La grille de notation sera affichée une fois les données DSF disponibles.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Remarques et recommandations du Chef d'agence (point 8) --}}
    <div class="modal fade" id="reportCaModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Remarques et recommandations du Chef d'agence</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small mb-3">Saisissez vos remarques et recommandations pour compléter la grille d'analyse critique (point 8).</p>
                    <form action="{{ route('ca.dossier.set.analyse') }}" method="post">
                        @csrf
                        <input type="hidden" name="dossier_id" value="{{ $item->id }}">
                        <input type="hidden" name="sequence" value="8">
                        <div class="mb-3">
                            <div id="quill-editor-ca-show" class="mb-3" style="height: 150px;"></div>
                            <textarea rows="3" class="d-none" name="content" id="quill-editor-area-ca-show">{{ $item->conclusions_ca ?? '' }}</textarea>
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

@section('modal')
<div class="modal fade" id="critereModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Choix de la valeur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('ca.instruction.critere.reponse') }}" method="post">
                    @csrf
                    <input type="hidden" id="_dossier_id" name="dossier_id">
                    <input type="hidden" id="_programme_id" name="programme_id">
                    <input type="hidden" id="critere_id_input" name="critere_id">
                    <div class="mb-3">
                        <label id="critere_name" class="form-label"></label>
                        <select required name="choice_id" id="critere_choices" class="form-select"></select>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('quill-editor-ca-show')) {
        var editor = new Quill('#quill-editor-ca-show', {
            theme: 'snow',
            modules: { toolbar: [ [{ 'header': [1, 2, false] }], ['bold', 'italic', 'underline', 'strike'], ['blockquote', 'code-block'], [{ 'list': 'ordered' }, { 'list': 'bullet' }], ['link'], ['clean'] ] }
        });
        var quillArea = document.getElementById('quill-editor-area-ca-show');
        editor.root.innerHTML = quillArea.value || '';
        editor.on('text-change', function() { quillArea.value = editor.root.innerHTML; });
        document.getElementById('reportCaModal').addEventListener('show.bs.modal', function() {
            editor.root.innerHTML = quillArea.value || '';
        });
    }

    document.querySelectorAll('.btn-critere').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var url = "{{ route('ca.instruction.critere.choices') }}";
            var name = this.dataset.name;
            var dossierId = this.dataset.dossier_id;
            var programmeId = this.dataset.programme_id;
            var id = this.dataset.id;

            document.getElementById('critere_name').textContent = name;
            document.getElementById('_dossier_id').value = dossierId;
            document.getElementById('_programme_id').value = programmeId;
            document.getElementById('critere_id_input').value = id;

            fetch(url + '?id=' + id)
                .then(r => r.json())
                .then(function(data) {
                    var select = document.getElementById('critere_choices');
                    select.innerHTML = '<option value="">Choisir...</option>';
                    data.forEach(function(choice) {
                        var opt = document.createElement('option');
                        opt.value = choice.id;
                        opt.textContent = choice.valeur;
                        select.appendChild(opt);
                    });
                });
        });
    });
});
</script>
@endsection

@section('script')
<style>
.table-notation th { font-weight: 600; }
.table-notation .vertical-align { vertical-align: middle !important; text-align: center; }
</style>
@endsection
