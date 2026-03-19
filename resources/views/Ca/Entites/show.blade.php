@extends('Layouts.ca')

@section('title', $item->name)
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('ca.dashboard') }}">Angara</a></li>
        <li class="breadcrumb-item"><a href="{{ route('ca.entites.index') }}">Entités individuelles</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($item->name, 40) }}</li>
    </ol>
</nav>
@endsection
@section('actions')
<div class="dropdown">
    <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="demo-psi-dot-vertical me-1"></i> Actions
    </button>
    <ul class="dropdown-menu dropdown-menu-end">
        <li><a class="dropdown-item" data-bs-target="#addProgModal" data-bs-toggle="modal" href="#"><i class="demo-psi-affiliate me-2"></i>Affecter à un programme</a></li>
        <li><a class="dropdown-item" href="{{ route('ca.entreprise.get.engagements',$item->token) }}"><i class="demo-psi-file-text-image me-2"></i>État des engagements</a></li>
    </ul>
</div>
@endsection

@section('page-header')
<div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <h5 class="page-title mb-0">{{ $item->name }}</h5>
        @if($item->prospect)
            <span class="badge bg-danger">Prospect</span>
        @endif
        <span class="badge bg-secondary">{{ $item->taille }}</span>
        <span class="badge bg-{{ $item->caractere === 'Formel' ? 'success' : 'warning' }}">{{ $item->caractere }}</span>
    </div>
    <p class="text-body-secondary mb-0 mt-1">Dossier entité individuelle — {{ $item->forme?->name ?? '—' }}</p>
</div>
@endsection

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
    </div>
@endif

<div class="row g-3 mb-4">
    <div class="col-md-3 col-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body py-3">
                <small class="text-muted text-uppercase d-block mb-1">Capital social</small>
                <p class="mb-0 fw-semibold">{{ number_format($item->capital ?? 0, 0, ',', ' ') }} XAF</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body py-3">
                <small class="text-muted text-uppercase d-block mb-1">Effectif</small>
                <p class="mb-0 fw-semibold">{{ ($item->nb_personnel_permanent ?? 0) + ($item->nb_personnel_saisonier ?? 0) }} employés</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body py-3">
                <small class="text-muted text-uppercase d-block mb-1">Dirigeant</small>
                <p class="mb-0 fw-semibold">{{ $item->manager ?? '—' }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body py-3">
                <small class="text-muted text-uppercase d-block mb-1">Localisation</small>
                <p class="mb-0 fw-semibold">{{ $item->arrondissement?->name ?? $item->region?->name ?? '—' }}</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 py-3">
                <h6 class="mb-0 fw-semibold"><i class="demo-psi-information me-2 text-primary"></i>Identification</h6>
            </div>
            <div class="card-body">
                <dl class="row mb-0 g-2">
                    <dt class="col-sm-5 text-muted small">Dénomination</dt>
                    <dd class="col-sm-7">{{ $item->name }}</dd>
                    <dt class="col-sm-5 text-muted small">RCCM</dt>
                    <dd class="col-sm-7">{{ $item->rccm ?? '—' }}</dd>
                    <dt class="col-sm-5 text-muted small">NIU</dt>
                    <dd class="col-sm-7">{{ $item->niu ?? '—' }}</dd>
                    <dt class="col-sm-5 text-muted small">CNPS</dt>
                    <dd class="col-sm-7">{{ $item->cnps ?? '—' }}</dd>
                    <dt class="col-sm-5 text-muted small">Mobile Money</dt>
                    <dd class="col-sm-7">{{ $item->mm_phone ?? '—' }}</dd>
                    <dt class="col-sm-5 text-muted small">Forme juridique</dt>
                    <dd class="col-sm-7">{{ $item->forme?->name ?? '—' }}</dd>
                    <dt class="col-sm-5 text-muted small">Système comptable</dt>
                    <dd class="col-sm-7">{{ $item->systeme ?? '—' }}</dd>
                    <dt class="col-sm-5 text-muted small">Capital social</dt>
                    <dd class="col-sm-7">{{ number_format($item->capital ?? 0, 0, ',', ' ') }} XAF</dd>
                    <dt class="col-sm-5 text-muted small">Création</dt>
                    <dd class="col-sm-7">{{ $item->dt_creation ? \Carbon\Carbon::parse($item->dt_creation)->format('d/m/Y') : '—' }}</dd>
                    <dt class="col-sm-5 text-muted small">Début activités</dt>
                    <dd class="col-sm-7">{{ $item->dt_start ? \Carbon\Carbon::parse($item->dt_start)->format('d/m/Y') : '—' }}</dd>
                    <dt class="col-sm-5 text-muted small">Ressources propres</dt>
                    <dd class="col-sm-7">{{ number_format($item->ressources_propres ?? 0, 0, ',', ' ') }} XAF</dd>
                    <dt class="col-sm-5 text-muted small">Total actif</dt>
                    <dd class="col-sm-7">{{ number_format($item->total_actif ?? 0, 0, ',', ' ') }} XAF</dd>
                    <dt class="col-sm-5 text-muted small">Effectif permanent</dt>
                    <dd class="col-sm-7">{{ $item->nb_personnel_permanent ?? 0 }}</dd>
                    <dt class="col-sm-5 text-muted small">Effectif saisonnier</dt>
                    <dd class="col-sm-7">{{ $item->nb_personnel_saisonier ?? 0 }}</dd>
                </dl>
                <h6 class="fw-semibold mt-4 mb-2"><i class="demo-psi-male me-2 text-primary"></i>Dirigeant</h6>
                <dl class="row mb-0 g-2">
                    <dt class="col-sm-5 text-muted small">Nom</dt>
                    <dd class="col-sm-7">{{ $item->manager ?? '—' }}</dd>
                    <dt class="col-sm-5 text-muted small">Sexe</dt>
                    <dd class="col-sm-7">{{ $item->manager_sexe ?? '—' }}</dd>
                    <dt class="col-sm-5 text-muted small">Naissance</dt>
                    <dd class="col-sm-7">{{ $item->manager_dtn ? \Carbon\Carbon::parse($item->manager_dtn)->format('d/m/Y') . ' (' . \Carbon\Carbon::parse($item->manager_dtn)->age . ' ans)' : '—' }}</dd>
                    <dt class="col-sm-5 text-muted small">Niveau</dt>
                    <dd class="col-sm-7">{{ $item->manager_niveau ?? '—' }}</dd>
                    <dt class="col-sm-5 text-muted small">Promoteur</dt>
                    <dd class="col-sm-7">{{ $item->manager_promoteur ? 'Oui' : 'Non' }}</dd>
                </dl>
                <h6 class="fw-semibold mt-4 mb-2"><i class="demo-psi-map me-2 text-primary"></i>Localisation & contact</h6>
                <dl class="row mb-0 g-2">
                    <dt class="col-sm-5 text-muted small">Quartier / Village</dt>
                    <dd class="col-sm-7">{{ trim(($item->village?->name ?? '') . ' ' . ($item->quartier?->name ?? '')) ?: '—' }}</dd>
                    <dt class="col-sm-5 text-muted small">Commune</dt>
                    <dd class="col-sm-7">{{ $item->arrondissement?->name ?? '—' }}</dd>
                    <dt class="col-sm-5 text-muted small">Département</dt>
                    <dd class="col-sm-7">{{ $item->departement?->name ?? '—' }}</dd>
                    <dt class="col-sm-5 text-muted small">Région</dt>
                    <dd class="col-sm-7">{{ $item->region?->name ?? '—' }}</dd>
                    <dt class="col-sm-5 text-muted small">Téléphone</dt>
                    <dd class="col-sm-7">{{ $item->phone ?? '—' }}</dd>
                    <dt class="col-sm-5 text-muted small">Email</dt>
                    <dd class="col-sm-7">{{ $item->email ?? '—' }}</dd>
                </dl>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="tab-base">
                    <!-- Nav tabs -->
                    <ul class="nav nav-underline nav-component border-bottom" role="tablist">
                       <li class="nav-item" role="presentation">
                          <button class="nav-link px-3 active" data-bs-toggle="tab" data-bs-target="#_tab1" type="button" role="tab" aria-controls="tab1" aria-selected="true">Objet Social</button>
                       </li>
                       <li class="nav-item" role="presentation">
                          <button class="nav-link px-3" data-bs-toggle="tab" data-bs-target="#_tab2" type="button" role="tab" aria-controls="tab2" aria-selected="false" tabindex="-1">Appuis sollicités</button>
                       </li>
                       <li class="nav-item" role="presentation">
                          <button class="nav-link px-3" data-bs-toggle="tab" data-bs-target="#_tab3" type="button" role="tab" aria-controls="tab3" aria-selected="false" tabindex="-1">Les tiers</button>
                       </li>
                       <li class="nav-item" role="presentation">
                            <button class="nav-link px-3" data-bs-toggle="tab" data-bs-target="#_tab4" type="button" role="tab" aria-controls="tab4" aria-selected="false" tabindex="-1">La mise en relation</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link px-3" data-bs-toggle="tab" data-bs-target="#_tab5" type="button" role="tab" aria-controls="tab5" aria-selected="false" tabindex="-1">État des engagements</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link px-3" data-bs-toggle="tab" data-bs-target="#_tab6" type="button" role="tab" aria-controls="tab6" aria-selected="false" tabindex="-1">Programmes</button>
                         </li>
                         <li class="nav-item" role="presentation">
                            <button class="nav-link px-3" data-bs-toggle="tab" data-bs-target="#_tab7" type="button" role="tab" aria-controls="tab7" aria-selected="false" tabindex="-1">Pièces constitutives</button>
                         </li>
                    </ul>


                    <!-- Tabs content -->
                    <div class="tab-content">
                       <div id="_tab1" class="tab-pane fade active show" role="tabpanel" aria-labelledby="home-tab">
                            <h5 class="fw-semibold mb-3"><i class="demo-psi-box me-2 text-primary"></i>Objet social</h5>
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td>
                                            <span>PRODUIT PRINCIPAL </span><span class="fw-900 fs-5 text-primary"><strong>{{ $item->produit?->name }}</strong></span>
                                        </td>
                                        <td>
                                            <span>Filiere </span><span class="fw-900 fs-5 text-primary"><strong>{{ $item->filiere?->name }}</strong></span>
                                        </td>
                                        <td>
                                            <span>Branche </span><span class="fw-900 fs-5 text-primary"><strong>{{ $item->branche?->name }}</strong></span>
                                        </td>
                                        <td>
                                            <span>Ancienneté dans ce produit: </span><span class="fw-900 fs-5 text-primary">depuis <strong> {{ $item->produit_year_start }}</strong></span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <h6 class="fw-semibold mt-4 mb-2">Autres produits ou services</h6>
                            <table class="table table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th>Produit</th>
                                        <th>Filiere</th>
                                        <th>Branche</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($item->produits as $p)
                                        <tr>
                                            <td>{{ $p->name }}</td>
                                            <td>{{ $p->filiere?->name }}</td>
                                            <td>{{ $p->branche?->name }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                       </div>
                       <div id="_tab2" class="tab-pane fade" role="tabpanel" aria-labelledby="profile-tab">
                            <h5>LISTE DES APPUIS SOLLICITES</h5>
                            <table class="table table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th>Service</th>
                                        <th>Type</th>
                                        <th>Nature</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($item->appuis as $service)
                                        <tr>
                                            <td>{{ $service->name }}</td>
                                            <td>{{ $service->type?->name }}</td>
                                            <td>{{ $service->financier?'Financier':'Non financier' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                       </div>
                       <div id="_tab3" class="tab-pane fade table-responsive" role="tabpanel" aria-labelledby="contact-tab">
                            <fieldset class="mt-4">
                                <legend>LISTES DES TIERS PERSONNE PHYSIQUE</legend>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>NOM</th>
                                            <th>NIU</th>
                                            <th>EMAIL</th>
                                            <th>TELEPHONE</th>
                                            <th>ADRESSE</th>
                                            <th>LIEN</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($item->tiers->where('person_id','!=',0) as $tier)
                                            <tr>
                                                <th>{{ $tier->person->name }}</th>
                                                <td>{{ $tier->person->niu }}</td>
                                                <td>{{ $tier->person->email }}</td>
                                                <td>{{ $tier->person->phone }}</td>
                                                <td>{{ $tier->person->address }}</td>
                                                <th>{{ $tier->lien }}</th>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </fieldset>

                            <fieldset class="mt-4">
                                <legend>LISTES DES TIERS PERSONNE MORALE</legend>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>DESIGNATION</th>
                                            <th>EMAIL</th>
                                            <th>TELEPHONE</th>
                                            <th>PRODUIT/SERVICE</th>
                                            <th>MANAGER</th>
                                            <th>LIEN</th>
                                            <th>I/E</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($item->tiers->where('company_id','!=',0) as $tier)
                                            <tr>
                                                <th>{{ $tier->company->name }}</th>
                                                <td>{{ $tier->company->email }}</td>
                                                <td>{{ $tier->company->phone }}</td>
                                                <td>{{ $tier->company->produit?->name ?? '—' }}</td>
                                                <td>{{ $tier->company->manager }}</td>
                                                <th>{{ $tier->lien }}</th>
                                                <td><span class="badge bg-{{ $tier->company->prospect?'danger':'success' }}">{{ $tier->company->prospect?'E':'I' }}</span></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </fieldset>

                       </div>
                       <div id="_tab4" class="tab-pane fade" role="tabpanel" aria-labelledby="contact-tab">
                            <h5>RESULTATS DU QUESTIONNAIRE DE MISE EN RELATION</h5>

                            <div class="accordion accordion-flush" id="_dm-flushAccordion">
                                @foreach ($mr as $r)
                                <div class="accordion-item">
                                    <div class="accordion-header" id="_p_acc_{{ $r['critere']->id }}">
                                       <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#_acc_{{ $r['critere']->id }}" aria-expanded="false" aria-controls="_dm-flushAccCollapseOne">
                                         {{ $r['critere']->name }}
                                       </button>
                                    </div>
                                    <div id="_acc_{{ $r['critere']->id }}" class="accordion-collapse collapse" aria-labelledby="_acc_{{ $r['critere']->id }}" data-bs-parent="#_dm-flushAccordion" style="">
                                            <div class="accordion-body">
                                                <div class="tab-base tab-vertical d-flex">
                                                    <!-- Nav tabs -->
                                                    <ul class="nav nav-tabs" style="max-height: 60vh; overflow: scroll; width: 300px;" role="tablist">
                                                        @foreach($r['items'] as $it)
                                                        <li class="nav-item" role="presentation">
                                                            <button class="nav-link {{ $loop->index==0?'active':'' }}" data-bs-toggle="tab" data-bs-target="#_vtab_{{ $it['sous_critere']->id }}" type="button" role="tab" aria-controls="tab_{{ $it['sous_critere']->id }}" aria-selected="true">{{ $it['sous_critere']->name }}</button>
                                                        </li>
                                                        @endforeach
                                                    </ul>
                                                    <!-- Tabs content -->
                                                    <div class="tab-content flex-fill">
                                                        @foreach($r['items'] as $it)
                                                            <div id="_vtab_{{ $it['sous_critere']->id }}" class="tab-pane fade {{ $loop->index==0?'show active':'' }}" role="tabpanel" aria-labelledby="v{{ $it['sous_critere']->id }}-tab">
                                                                <table class="table table-sm table-striped">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Question</th>
                                                                            <th></th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($it['items'] as $rep)
                                                                            <tr>
                                                                                <td>{{ $rep->question->name }}</td>
                                                                                <td>{{ $rep->choice?->name }}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                       </div>
                                    </div>
                                @endforeach
                             </div>
                       </div>
                       <div id="_tab5" class="tab-pane fade" role="tabpanel" aria-labelledby="contact-tab">
                            <h5 class="fw-semibold mb-3">État des engagements</h5>
                            <p class="mb-3">
                                <a href="{{ route('ca.entreprise.get.engagements',$item->token) }}" class="btn btn-primary">
                                    <i class="demo-psi-file-text-image me-2"></i>Voir l'état des engagements
                                </a>
                            </p>
                       </div>
                       <div id="_tab6" class="tab-pane fade" role="tabpanel" aria-labelledby="contact-tab">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Programme</th>
                                        <th>signataire</th>
                                        <th>Budget</th>
                                        <th>Date signature conv.</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($item->dossiers as $dossier)
                                        <tr>
                                            <th><a href="{{ route('ca.programmes.show',$dossier->programme?->token) }}">{{ $dossier->programme?->name }}</a></th>
                                            <td>{{ $dossier->programme?->signataire }}</td>
                                            <td>{{ number_format($dossier->programme?->budget,0,',','.') }} XAF</td>
                                            <td>{{ \Carbon\Carbon::parse($dossier->programme?->dt_sig_conv)->format('d/m/Y') }}</td>
                                            <td>

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                       </div>
                       <div id="_tab7" class="tab-pane fade" role="tabpanel" aria-labelledby="fichier-tab">

                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <th>DOCUMENT</th>
                                    <th>LIEN</th>
                                    <th></th>
                                </thead>
                                <tbody>
                                    @foreach ($item->elements as $element)
                                       <tr>
                                            <td>{{ $element->type?->name }}</td>
                                            <td><a class="btn-link" href="{{ $element->path }}">Cliquer ici </a></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                   </div>
                    </div>
                 </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addProgModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Affectation à un programme</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" action="{{ route('ca.entite.programme.save') }}" method="post">
                        @csrf
                        <input type="hidden" name="entreprise_id" value="{{ $item->id }}">
                        <input type="hidden" name="gestionnaire_id" value="{{ $item->user_id }}">
                            <div class="">
                                <div class="mb-3">
                                    <label for="programme_id" class="form-label">Programme</label>
                                    <select required name="programme_id" id="programme_id" class="form-select">
                                        <option value="0">Selectionner un programme ...</option>
                                        @foreach($programmes as $it)
                                            <option value="{{ $it->id }}">{{ $it->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="analyste_id" class="form-label">Analyste financier</label>
                                    <select required name="analyste_id" id="analyste_id" class="form-select">
                                        <option value="0">Selectionner un analyste ...</option>
                                        @foreach($analystes as $it)
                                            <option value="{{ $it->id }}">{{ $it->name }}-{{ $it->agence?->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        <div class="mt-5">
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <style>
        .form-group{
            margin-top: 1rem;
        }
    </style>
@endsection
