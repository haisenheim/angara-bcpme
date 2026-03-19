@extends('Layouts.analyste')

@section('title', $item->name)
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('analyste.dashboard') }}">Angara</a></li>
        <li class="breadcrumb-item"><a href="{{ route('analyste.entreprises.index') }}">Entreprises</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $item->name }}</li>
    </ol>
</nav>
@endsection

@section('actions')
    <div class="dropdown">
        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Actions">
            <i class="demo-psi-dot-vertical"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="{{ route('analyste.entreprise.get.engagements', $item->token) }}"><i class="demo-psi-file-text-image me-2"></i> État des engagements</a></li>
            <li><a class="dropdown-item" href="{{ route('analyste.entreprise.questionnaire', $item->token) }}"><i class="demo-psi-pen-5 me-2"></i> Questionnaire de mise en relation</a></li>
            <li><a class="dropdown-item" href="{{ route('analyste.entreprise.physique.create', $item->token) }}"><i class="demo-psi-add-user me-2"></i> Tiers personne physique</a></li>
            <li><a class="dropdown-item" href="{{ route('analyste.entreprise.morale.create', $item->token) }}"><i class="demo-psi-building me-2"></i> Tiers personne morale</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#addProgModal"><i class="demo-psi-add me-2"></i> Affecter à un programme</a></li>
        </ul>
    </div>
@endsection

@section('page-header')
    <div>
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
            <div>
                <h5 class="page-title mb-0 mt-2">{{ $item->name }}</h5>
                <p class="lead mb-0 text-muted">{{ $item->forme?->name ?? '' }} — {{ $item->region?->name ?? '' }}</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                @if($item->prospect)
                    <span class="badge bg-danger">Prospect</span>
                @endif
                <span class="badge bg-primary">{{ $dossiersAnalyste->count() }} dossier(s)</span>
            </div>
        </div>
    </div>
@endsection

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    @endif

    {{-- Accès rapide aux dossiers --}}
    @if($dossiersAnalyste->isNotEmpty())
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent border-0 py-3 d-flex align-items-center justify-content-between">
                <h6 class="mb-0 fw-semibold"><i class="demo-psi-folder me-2 text-primary"></i>Mes dossiers d'instruction</h6>
                <a href="{{ route('analyste.dossiers.index') }}" class="btn btn-sm btn-outline-primary">Voir tous</a>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    @foreach($dossiersAnalyste as $dossier)
                        <div class="col-md-6 col-lg-4">
                            <div class="card border h-100">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <div>
                                        <strong>{{ $dossier->programme?->name ?? 'Programme' }}</strong>
                                        <small class="d-block text-muted">{{ $dossier->programme?->signataire ?? '' }}</small>
                                    </div>
                                    <a href="{{ route('analyste.dossiers.show', $dossier->token) }}" class="btn btn-sm btn-primary">
                                        <i class="demo-psi-eye me-1"></i> Voir
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <div class="row g-4">
        {{-- Colonne gauche : Identité, Dirigeant, Contact --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 py-3">
                    <h6 class="mb-0 fw-semibold"><i class="demo-psi-information me-2 text-primary"></i>Identité</h6>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-5 text-muted small">Désignation</dt>
                        <dd class="col-sm-7">{{ $item->name ?? '—' }}</dd>
                        <dt class="col-sm-5 text-muted small">RCCM</dt>
                        <dd class="col-sm-7">{{ $item->rccm ?? '—' }}</dd>
                        <dt class="col-sm-5 text-muted small">NIU</dt>
                        <dd class="col-sm-7">{{ $item->niu ?? '—' }}</dd>
                        <dt class="col-sm-5 text-muted small">CNPS</dt>
                        <dd class="col-sm-7">{{ $item->cnps ?? '—' }}</dd>
                        <dt class="col-sm-5 text-muted small">Forme juridique</dt>
                        <dd class="col-sm-7">{{ $item->forme?->name ?? '—' }}</dd>
                        <dt class="col-sm-5 text-muted small">Capital social</dt>
                        <dd class="col-sm-7">{{ $item->capital ? number_format($item->capital, 0, ',', ' ') . ' XAF' : '—' }}</dd>
                        <dt class="col-sm-5 text-muted small">Effectif</dt>
                        <dd class="col-sm-7">{{ $item->nb_personnel ? number_format($item->nb_personnel, 0, ',', ' ') : '—' }}</dd>
                        @if($item->dt_creation)
                            <dt class="col-sm-5 text-muted small">Création</dt>
                            <dd class="col-sm-7">{{ \Carbon\Carbon::parse($item->dt_creation)->format('d/m/Y') }}</dd>
                        @endif
                    </dl>
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-3">
                <div class="card-header bg-transparent border-0 py-3">
                    <h6 class="mb-0 fw-semibold"><i class="demo-psi-user me-2 text-primary"></i>Dirigeant</h6>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-5 text-muted small">Nom</dt>
                        <dd class="col-sm-7">{{ $item->manager ?? '—' }}</dd>
                        <dt class="col-sm-5 text-muted small">Sexe</dt>
                        <dd class="col-sm-7">{{ $item->manager_sexe ?? '—' }}</dd>
                        @if($item->manager_dtn)
                            <dt class="col-sm-5 text-muted small">Date de naissance</dt>
                            <dd class="col-sm-7">{{ \Carbon\Carbon::parse($item->manager_dtn)->format('d/m/Y') }}</dd>
                        @endif
                        <dt class="col-sm-5 text-muted small">Niveau d'instruction</dt>
                        <dd class="col-sm-7">{{ $item->manager_niveau ?? '—' }}</dd>
                        <dt class="col-sm-5 text-muted small">Promoteur</dt>
                        <dd class="col-sm-7">{{ $item->manager_promoteur ? 'Oui' : 'Non' }}</dd>
                    </dl>
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-3">
                <div class="card-header bg-transparent border-0 py-3">
                    <h6 class="mb-0 fw-semibold"><i class="demo-psi-mail me-2 text-primary"></i>Contact</h6>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-5 text-muted small">Téléphone</dt>
                        <dd class="col-sm-7">{{ $item->phone ?? '—' }}</dd>
                        <dt class="col-sm-5 text-muted small">Email</dt>
                        <dd class="col-sm-7">{{ $item->email ?? '—' }}</dd>
                        <dt class="col-sm-5 text-muted small">Localisation</dt>
                        <dd class="col-sm-7">{{ trim(($item->village?->name ?? '') . ' ' . ($item->quartier?->name ?? '')) ?: '—' }}</dd>
                        <dt class="col-sm-5 text-muted small">Commune</dt>
                        <dd class="col-sm-7">{{ $item->arrondissement?->name ?? '—' }}</dd>
                        <dt class="col-sm-5 text-muted small">Région</dt>
                        <dd class="col-sm-7">{{ $item->region?->name ?? '—' }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        {{-- Colonne droite : Onglets --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 px-0 pt-0">
                    <ul class="nav nav-tabs card-header-tabs" role="tablist">
                        <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-objet" type="button">Objet social</button></li>
                        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-appuis" type="button">Appuis</button></li>
                        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-tiers" type="button">Tiers</button></li>
                        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-mise" type="button">Mise en relation</button></li>
                        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-engagements" type="button">Engagements</button></li>
                        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-dossiers" type="button">Dossiers</button></li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div id="tab-objet" class="tab-pane fade show active" role="tabpanel">
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded">
                                        <small class="text-muted d-block">Produit principal</small>
                                        <strong>{{ $item->produit?->name ?? '—' }}</strong>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded">
                                        <small class="text-muted d-block">Filière</small>
                                        <strong>{{ $item->filiere?->name ?? '—' }}</strong>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded">
                                        <small class="text-muted d-block">Branche</small>
                                        <strong>{{ $item->branche?->name ?? '—' }}</strong>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded">
                                        <small class="text-muted d-block">Ancienneté produit</small>
                                        <strong>depuis {{ $item->produit_year_start ?? '—' }}</strong>
                                    </div>
                                </div>
                            </div>
                            @if($item->produits->isNotEmpty())
                                <h6 class="mb-2">Autres produits</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover">
                                        <thead class="table-light"><tr><th>Produit</th><th>Filière</th><th>Branche</th></tr></thead>
                                        <tbody>
                                            @foreach($item->produits as $p)
                                                <tr>
                                                    <td>{{ $p->name }}</td>
                                                    <td>{{ $p->filiere?->name ?? '—' }}</td>
                                                    <td>{{ $p->branche?->name ?? '—' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>

                        <div id="tab-appuis" class="tab-pane fade" role="tabpanel">
                            @if($item->appuis->isNotEmpty())
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light"><tr><th>Service</th><th>Type</th><th>Nature</th></tr></thead>
                                        <tbody>
                                            @foreach($item->appuis as $s)
                                                <tr>
                                                    <td>{{ $s->name }}</td>
                                                    <td>{{ $s->type?->name ?? '—' }}</td>
                                                    <td><span class="badge bg-{{ $s->financier ? 'primary' : 'secondary' }}">{{ $s->financier ? 'Financier' : 'Non financier' }}</span></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted mb-0">Aucun appui sollicité.</p>
                            @endif
                        </div>

                        <div id="tab-tiers" class="tab-pane fade" role="tabpanel">
                            @php $tiersPhysique = $item->tiers->where('person_id', '!=', 0); $tiersMorale = $item->tiers->where('company_id', '!=', 0); @endphp
                            @if($tiersPhysique->isNotEmpty())
                                <h6 class="mb-2">Personnes physiques</h6>
                                <div class="table-responsive mb-4">
                                    <table class="table table-sm table-hover">
                                        <thead class="table-light"><tr><th>Nom</th><th>NIU</th><th>Contact</th><th>Lien</th></tr></thead>
                                        <tbody>
                                            @foreach($tiersPhysique as $t)
                                                <tr>
                                                    <td>{{ $t->person?->name ?? '—' }}</td>
                                                    <td>{{ $t->person?->niu ?? '—' }}</td>
                                                    <td>{{ $t->person?->phone ?? $t->person?->email ?? '—' }}</td>
                                                    <td>{{ $t->lien ?? '—' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                            @if($tiersMorale->isNotEmpty())
                                <h6 class="mb-2">Personnes morales</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover">
                                        <thead class="table-light"><tr><th>Désignation</th><th>Contact</th><th>Produit</th><th>Lien</th></tr></thead>
                                        <tbody>
                                            @foreach($tiersMorale as $t)
                                                <tr>
                                                    <td>{{ $t->company?->name ?? '—' }}</td>
                                                    <td>{{ $t->company?->phone ?? $t->company?->email ?? '—' }}</td>
                                                    <td>{{ $t->company?->produit?->name ?? '—' }}</td>
                                                    <td>
                                                        {{ $t->lien ?? '—' }}
                                                        @if($t->company)
                                                            <span class="badge bg-{{ $t->company->prospect ? 'danger' : 'success' }} ms-1">{{ $t->company->prospect ? 'E' : 'I' }}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                            @if($tiersPhysique->isEmpty() && $tiersMorale->isEmpty())
                                <p class="text-muted mb-0">Aucun tiers enregistré.</p>
                            @endif
                        </div>

                        <div id="tab-mise" class="tab-pane fade" role="tabpanel">
                            @if(count($mr ?? []))
                                <div class="accordion" id="accordionMise">
                                    @foreach($mr as $r)
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#acc_{{ $r['critere']->id }}">
                                                    {{ $r['critere']->name }}
                                                </button>
                                            </h2>
                                            <div id="acc_{{ $r['critere']->id }}" class="accordion-collapse collapse" data-bs-parent="#accordionMise">
                                                <div class="accordion-body">
                                                    @foreach($r['items'] as $it)
                                                        <h6 class="mt-3">{{ $it['sous_critere']->name }}</h6>
                                                        <table class="table table-sm">
                                                            <tbody>
                                                                @foreach($it['items'] as $rep)
                                                                    <tr><td>{{ $rep->question?->name ?? '' }}</td><td>{{ $rep->choice?->name ?? '—' }}</td></tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted mb-0">Aucun questionnaire renseigné.</p>
                            @endif
                        </div>

                        <div id="tab-engagements" class="tab-pane fade" role="tabpanel">
                            <p class="text-muted mb-2">État des engagements de l'entreprise.</p>
                            <a href="{{ route('analyste.entreprise.get.engagements', $item->token) }}" class="btn btn-outline-primary">
                                <i class="demo-psi-file-text-image me-2"></i> Consulter les engagements
                            </a>
                        </div>

                        <div id="tab-dossiers" class="tab-pane fade" role="tabpanel">
                            @if($dossiersAnalyste->isNotEmpty())
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr><th>Programme</th><th>Signataire</th><th>Budget</th><th>Date signature</th><th>Actions</th></tr>
                                        </thead>
                                        <tbody>
                                            @foreach($dossiersAnalyste as $d)
                                                <tr>
                                                    <td><a href="{{ route('analyste.programmes.show', $d->programme?->token) }}" class="text-decoration-none">{{ $d->programme?->name ?? '—' }}</a></td>
                                                    <td>{{ $d->programme?->signataire ?? '—' }}</td>
                                                    <td>{{ $d->programme?->budget ? number_format($d->programme->budget, 0, ',', ' ') . ' XAF' : '—' }}</td>
                                                    <td>{{ $d->programme?->dt_sig_conv ? \Carbon\Carbon::parse($d->programme->dt_sig_conv)->format('d/m/Y') : '—' }}</td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown"><i class="demo-psi-dot-vertical"></i></button>
                                                            <ul class="dropdown-menu dropdown-menu-end">
                                                                <li><a class="dropdown-item" href="{{ route('analyste.dossiers.show', $d->token) }}"><i class="demo-psi-eye me-2"></i> Voir le dossier</a></li>
                                                                <li><a class="dropdown-item" href="{{ route('analyste.programmes.show', $d->programme?->token) }}"><i class="demo-psi-file me-2"></i> Voir le programme</a></li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted mb-0">Aucun dossier d'instruction.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addProgModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Affecter à un programme</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <form action="{{ route('analyste.entreprise.programme.save') }}" method="post">
                    @csrf
                    <input type="hidden" name="entreprise_id" value="{{ $item->id }}">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Programme</label>
                            <select name="programme_id" class="form-select" required>
                                <option value="">Sélectionner un programme...</option>
                                @foreach($programmes as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Analyste financier</label>
                            <select name="analyste_id" class="form-select" required>
                                <option value="">Sélectionner un analyste...</option>
                                @foreach($analystes as $a)
                                    <option value="{{ $a->id }}">{{ $a->name }} — {{ $a->agence?->name ?? '' }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
