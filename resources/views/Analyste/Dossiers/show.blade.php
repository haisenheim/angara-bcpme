@extends('Layouts.analyste')

@section('title', 'Dossier - ' . ($item->entreprise?->name ?? 'Instruction'))
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('analyste.dashboard') }}">Angara</a></li>
        <li class="breadcrumb-item"><a href="{{ route('analyste.dossiers.index') }}">Dossiers d'instruction</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $item->entreprise?->name ?? $item->name ?? 'Dossier' }}</li>
    </ol>
</nav>
@endsection

@section('actions')
    <div class="dropdown">
        <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Actions">
            <i class="demo-psi-dot-vertical me-1"></i> Actions
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
            @if($item->entreprise)
                <li><a class="dropdown-item" href="{{ route('analyste.entreprise.get.engagements', $item->entreprise->token) }}"><i class="demo-psi-file-text-image me-2"></i> État des engagements</a></li>
            @endif
            <li><a class="dropdown-item" href="{{ route('analyste.dossier.get.grille.analyse', $item->token) }}"><i class="demo-psi-magnifi-glass me-2"></i> Grille d'analyse critique</a></li>
        </ul>
    </div>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Dossier d'instruction</h5>
        <p class="lead mb-0">{{ $item->entreprise?->name }} — {{ $item->programme?->name }}</p>
        @if($item->exploitation_analyste_assigned_at)
            <p class="small text-muted mb-0 mt-2">
                <span class="text-uppercase fw-semibold">Cotation du dossier</span> —
                le {{ $item->exploitation_analyste_assigned_at->format('d/m/Y') }} à {{ $item->exploitation_analyste_assigned_at->format('H:i') }}
                @if($item->exploitationAnalysteAssignedBy)
                    par <strong>{{ $item->exploitationAnalysteAssignedBy->name }}</strong>
                @endif
            </p>
        @endif
    </div>
@endsection

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    @endif
    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    @endif
    @error('submission')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    {{-- Résumé du dossier --}}
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
                    <p class="mb-0 fw-bold fs-5">{{ $item->note ?? '—' }}</p>
                </div>
            </div>
        </div>
        @if($sme)
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

    @if($item->analyste_id)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div>
                    <h6 class="mb-1 fw-semibold">Transmission au responsable exploitation</h6>
                    @if($item->isInstructionSubmittedToExploitation())
                        <p class="mb-0 small text-muted">
                            Dossier soumis le {{ $item->exploitation_instruction_submitted_at->format('d/m/Y à H:i') }}
                            @if($item->exploitationInstructionSubmittedBy)
                                — {{ $item->exploitationInstructionSubmittedBy->name }}
                            @endif
                        </p>
                    @else
                        <p class="mb-0 small text-muted">Lorsque l’instruction est terminée, soumettez le dossier pour que le responsable exploitation puisse consulter votre travail et statuer.</p>
                    @endif
                </div>
                @if(! $item->isInstructionSubmittedToExploitation())
                    <form method="post" action="{{ route('analyste.dossiers.soumettre-exploitation', $item) }}" class="mb-0">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            Soumettre au responsable exploitation pour validation
                        </button>
                    </form>
                @endif
            </div>
        </div>
    @endif

    <div class="row g-3">
        {{-- Colonne gauche : DSF et notation PME --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 py-3">
                    <h6 class="mb-0 fw-semibold"><i class="demo-psi-upload me-2 text-primary"></i>Import DSF</h6>
                </div>
                <div class="card-body">
                    <form enctype="multipart/form-data" action="{{ route('analyste.dossier.dsf') }}" method="post">
                        @csrf
                        <input type="hidden" name="dossier_id" value="{{ $item->id }}">
                        <div class="mb-3">
                            <label class="form-label">Année N</label>
                            <input type="number" required name="annee" class="form-control" placeholder="Ex: 2024" min="2000" max="2100">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Fichier DSF</label>
                            <input type="file" name="upload" class="form-control" accept=".xlsx,.xls,.csv">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="demo-psi-check me-2"></i> Enregistrer
                        </button>
                    </form>
                </div>
            </div>

            @if($sme)
                <div class="card border-0 shadow-sm mt-3">
                    <div class="card-header bg-transparent border-0 py-3">
                        <h6 class="mb-0 fw-semibold"><i class="demo-psi-information me-2 text-primary"></i>Notation PME</h6>
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
            @include('RoleSpace.dossiers.partials.instruction_grille_notation', [
                'item' => $item,
                'criteres' => $criteres,
                'indicateurs' => $indicateurs,
                'sme' => $sme,
                'readOnly' => false,
            ])
        </div>
    </div>

    <div class="modal fade" id="report1Modal">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Analyse</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <p id="description"></p>
                    <form action="{{ route('analyste.dossier.set.analyse') }}" method="post">
                        @csrf
                        <input type="hidden" name="dossier_id" value="{{ $item->id }}">
                        <input type="hidden" id="sequence" name="sequence">
                        <div class="mb-3">
                            <x-quill :name="'content'"></x-quill>
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
                <form action="{{ route('analyste.instruction.critere.reponse') }}" method="post">
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
    document.querySelectorAll('.btn-critere').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var url = "{{ route('analyste.instruction.critere.choices') }}";
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
