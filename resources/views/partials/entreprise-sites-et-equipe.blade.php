@php
    $item->loadMissing([
        'sites.arrondissement',
        'sites.departement',
        'sites.region',
        'equipeMembres.site',
        'equipeMembres.cniFichier',
    ]);

    $canCrud = $canCrud ?? request()->routeIs('gestionnaire.*');
    $siteStoreRoute = $siteStoreRoute ?? ($canCrud ? 'gestionnaire.entreprises.sites.store' : null);
    $siteUpdateRoute = $siteUpdateRoute ?? ($canCrud ? 'gestionnaire.entreprises.sites.update' : null);
    $siteDestroyRoute = $siteDestroyRoute ?? ($canCrud ? 'gestionnaire.entreprises.sites.destroy' : null);

    $equipeStoreRoute = $equipeStoreRoute ?? ($canCrud ? 'gestionnaire.entreprises.equipe.store' : null);
    $equipeUpdateRoute = $equipeUpdateRoute ?? ($canCrud ? 'gestionnaire.entreprises.equipe.update' : null);
    $equipeDestroyRoute = $equipeDestroyRoute ?? ($canCrud ? 'gestionnaire.entreprises.equipe.destroy' : null);

    $regions = \App\Models\Region::orderBy('name')->get(['id', 'name']);
    $departements = \App\Models\Departement::orderBy('name')->get(['id', 'name', 'region_id']);
    $arrondissements = \App\Models\Arrondissement::orderBy('name')->get(['id', 'name', 'departement_id']);

    $niveauEtudes = $niveauEtudes ?? ['Supérieur', 'Secondaire', 'Primaire', 'Sans niveau'];
@endphp

@if($siteStoreRoute || $equipeStoreRoute)
    @include('partials.summernote-fr-styles')
@endif

<div class="row g-3">
    <div class="col-12">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <strong>Sites</strong>
                @if($siteStoreRoute)
                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalAddEntrepriseSite">
                        Ajouter un site
                    </button>
                @endif
            </div>
            <div class="card-body">
                @if($item->sites->isEmpty())
                    <p class="text-body-secondary mb-0">Aucun site renseigné.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Libellé</th>
                                    <th>Commune</th>
                                    <th>Contact</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($item->sites as $site)
                                    <tr>
                                        <td class="fw-semibold">{{ $site->libelle }}</td>
                                        <td class="small">
                                            {{ $site->arrondissement?->name ?? '—' }}
                                            @if($site->departement || $site->region)
                                                <div class="text-muted">
                                                    {{ $site->departement?->name ?? '—' }} · {{ $site->region?->name ?? '—' }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="small">
                                            <div>{{ $site->telephone ?? '—' }}</div>
                                            <div class="text-muted">{{ $site->email ?? '—' }}</div>
                                        </td>
                                        <td class="text-end">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Actions
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <button class="dropdown-item" type="button" data-bs-toggle="modal" data-bs-target="#modalViewEntrepriseSite{{ $site->id }}">
                                                            Consulter
                                                        </button>
                                                    </li>
                                                    @if($siteUpdateRoute)
                                                        <li>
                                                            <button class="dropdown-item" type="button" data-bs-toggle="modal" data-bs-target="#modalEditEntrepriseSite{{ $site->id }}">
                                                                Modifier
                                                            </button>
                                                        </li>
                                                    @endif
                                                    @if($siteDestroyRoute)
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <form method="post" action="{{ route($siteDestroyRoute, ['token' => $item->token, 'site' => $site->id]) }}" onsubmit="return confirm('Supprimer ce site ?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item text-danger">Supprimer</button>
                                                            </form>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>

                                    <div class="modal fade" id="modalViewEntrepriseSite{{ $site->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Site — {{ $site->libelle }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <div class="text-muted small">Téléphone</div>
                                                            <div class="fw-semibold">{{ $site->telephone ?? '—' }}</div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="text-muted small">E-mail</div>
                                                            <div class="fw-semibold">{{ $site->email ?? '—' }}</div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="text-muted small">Région</div>
                                                            <div class="fw-semibold">{{ $site->region?->name ?? '—' }}</div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="text-muted small">Département</div>
                                                            <div class="fw-semibold">{{ $site->departement?->name ?? '—' }}</div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="text-muted small">Commune</div>
                                                            <div class="fw-semibold">{{ $site->arrondissement?->name ?? '—' }}</div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="text-muted small">Village / quartier</div>
                                                            <div class="fw-semibold">{{ $site->village_ou_quartier ?? '—' }}</div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="text-muted small">Latitude</div>
                                                            <div class="fw-semibold">{{ $site->latitude ?? '—' }}</div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="text-muted small">Longitude</div>
                                                            <div class="fw-semibold">{{ $site->longitude ?? '—' }}</div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="text-muted small mb-1">Divers</div>
                                                            <div class="border rounded p-2 bg-light">{!! $site->divers ?: '—' !!}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Fermer</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if($siteUpdateRoute)
                                        <div class="modal fade" id="modalEditEntrepriseSite{{ $site->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Modifier le site — {{ $site->libelle }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="post" action="{{ route($siteUpdateRoute, ['token' => $item->token, 'site' => $site->id]) }}">
                                                            @csrf
                                                            @method('PATCH')
                                                            <div class="row g-3">
                                                                <div class="col-md-6">
                                                                    <label class="form-label">Nom / libellé <span class="text-danger">*</span></label>
                                                                    <input type="text" name="libelle" class="form-control" required value="{{ $site->libelle }}">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label class="form-label">Téléphone</label>
                                                                    <input type="text" name="telephone" class="form-control" value="{{ $site->telephone }}">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label class="form-label">E-mail</label>
                                                                    <input type="email" name="email" class="form-control" value="{{ $site->email }}">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label class="form-label">Région</label>
                                                                    <select name="region_id" class="form-select">
                                                                        <option value="">Choisir...</option>
                                                                        @foreach($regions as $r)
                                                                            <option value="{{ $r->id }}" @selected((int) $site->region_id === (int) $r->id)>{{ $r->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label class="form-label">Département</label>
                                                                    <select name="departement_id" class="form-select">
                                                                        <option value="">Choisir...</option>
                                                                        @foreach($departements as $d)
                                                                            <option value="{{ $d->id }}" @selected((int) $site->departement_id === (int) $d->id)>{{ $d->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label class="form-label">Commune <span class="text-danger">*</span></label>
                                                                    <select name="arrondissement_id" class="form-select" required>
                                                                        <option value="">Choisir...</option>
                                                                        @foreach($arrondissements as $a)
                                                                            <option value="{{ $a->id }}" @selected((int) $site->arrondissement_id === (int) $a->id)>{{ $a->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label class="form-label">Village / quartier</label>
                                                                    <input type="text" name="village_ou_quartier" class="form-control" value="{{ $site->village_ou_quartier }}">
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label class="form-label">Latitude</label>
                                                                    <input type="text" name="latitude" class="form-control" value="{{ $site->latitude }}">
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label class="form-label">Longitude</label>
                                                                    <input type="text" name="longitude" class="form-control" value="{{ $site->longitude }}">
                                                                </div>
                                                                <div class="col-12">
                                                                    <label class="form-label">Divers</label>
                                                                    <div class="summernote-wrapper summernote-wrapper--compact">
                                                                        <textarea class="form-control js-summernote-fr" name="divers">{!! $site->divers !!}</textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="mt-4 d-flex justify-content-end gap-2">
                                                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                                                                <button type="submit" class="btn btn-primary">Enregistrer</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <strong>Équipe</strong>
                @if($equipeStoreRoute)
                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalAddEntrepriseEquipe">
                        Ajouter un membre
                    </button>
                @endif
            </div>
            <div class="card-body">
                @if($item->equipeMembres->isEmpty())
                    <p class="text-body-secondary mb-0">Aucun membre d’équipe renseigné.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Nom</th>
                                    <th>Fonction</th>
                                    <th>Contact</th>
                                    <th>Site</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($item->equipeMembres as $m)
                                    @php
                                        $age = $m->date_naissance ? \Carbon\Carbon::parse($m->date_naissance)->age : null;
                                    @endphp
                                    <tr>
                                        <td class="fw-semibold">
                                            {{ trim(($m->nom ?? '') . ' ' . ($m->prenom ?? '')) ?: '—' }}
                                            @if($m->dirigeant)
                                                <span class="badge bg-primary ms-1">Dirigeant</span>
                                            @endif
                                            @if($m->associe)
                                                <span class="badge bg-success ms-1">Associé</span>
                                            @endif
                                            @if($age !== null)
                                                <div class="text-muted small">{{ $age }} ans</div>
                                            @endif
                                        </td>
                                        <td class="small">
                                            {{ $m->fonction ?? '—' }}
                                            @if($m->niveau_etude)
                                                <div class="text-muted">{{ $m->niveau_etude }}</div>
                                            @endif
                                        </td>
                                        <td class="small">
                                            <div>{{ $m->telephone ?? '—' }}</div>
                                            <div class="text-muted">{{ $m->email ?? '—' }}</div>
                                        </td>
                                        <td class="small">{{ $m->site?->libelle ?? '—' }}</td>
                                        <td class="text-end">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Actions
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <button class="dropdown-item" type="button" data-bs-toggle="modal" data-bs-target="#modalViewEntrepriseMembre{{ $m->id }}">
                                                            Consulter
                                                        </button>
                                                    </li>
                                                    @if($equipeUpdateRoute)
                                                        <li>
                                                            <button class="dropdown-item" type="button" data-bs-toggle="modal" data-bs-target="#modalEditEntrepriseMembre{{ $m->id }}">
                                                                Modifier
                                                            </button>
                                                        </li>
                                                    @endif
                                                    @if($m->cniFichier?->path)
                                                        <li>
                                                            <a class="dropdown-item" href="{{ $m->cniFichier->path }}" target="_blank">Voir CNI</a>
                                                        </li>
                                                    @endif
                                                    @if($equipeDestroyRoute)
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <form method="post" action="{{ route($equipeDestroyRoute, ['token' => $item->token, 'membre' => $m->id]) }}" onsubmit="return confirm('Supprimer ce membre ?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item text-danger">Supprimer</button>
                                                            </form>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>

                                    <div class="modal fade" id="modalViewEntrepriseMembre{{ $m->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Membre — {{ trim(($m->nom ?? '') . ' ' . ($m->prenom ?? '')) ?: '—' }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <div class="text-muted small">Téléphone</div>
                                                            <div class="fw-semibold">{{ $m->telephone ?? '—' }}</div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="text-muted small">E-mail</div>
                                                            <div class="fw-semibold">{{ $m->email ?? '—' }}</div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="text-muted small">Fonction</div>
                                                            <div class="fw-semibold">{{ $m->fonction ?? '—' }}</div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="text-muted small">Niveau d’étude</div>
                                                            <div class="fw-semibold">{{ $m->niveau_etude ?? '—' }}</div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="text-muted small">Site</div>
                                                            <div class="fw-semibold">{{ $m->site?->libelle ?? '—' }}</div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="text-muted small">Date de naissance</div>
                                                            <div class="fw-semibold">{{ $m->date_naissance ? \Carbon\Carbon::parse($m->date_naissance)->format('d/m/Y') : '—' }}</div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="text-muted small">Spécialité</div>
                                                            <div class="fw-semibold">{{ $m->specialite ?? '—' }}</div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="text-muted small">Associé</div>
                                                            <div class="fw-semibold">{{ $m->associe ? 'Oui' : 'Non' }}</div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="text-muted small">Dirigeant</div>
                                                            <div class="fw-semibold">{{ $m->dirigeant ? 'Oui' : 'Non' }}</div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="text-muted small">Numéro CNI</div>
                                                            <div class="fw-semibold">{{ $m->cni_numero ?? '—' }}</div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="text-muted small">Expiration CNI</div>
                                                            <div class="fw-semibold">{{ $m->cni_expire_at ? \Carbon\Carbon::parse($m->cni_expire_at)->format('d/m/Y') : '—' }}</div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="text-muted small">Fichier CNI</div>
                                                            <div class="fw-semibold">
                                                                @if($m->cniFichier?->path)
                                                                    <a href="{{ $m->cniFichier->path }}" target="_blank">Ouvrir</a>
                                                                @else
                                                                    —
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="text-muted small mb-1">Divers</div>
                                                            <div class="border rounded p-2 bg-light">{!! $m->divers ?: '—' !!}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Fermer</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if($equipeUpdateRoute)
                                        <div class="modal fade" id="modalEditEntrepriseMembre{{ $m->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Modifier le membre — {{ trim(($m->nom ?? '') . ' ' . ($m->prenom ?? '')) ?: '—' }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="post" action="{{ route($equipeUpdateRoute, ['token' => $item->token, 'membre' => $m->id]) }}" enctype="multipart/form-data">
                                                            @csrf
                                                            @method('PATCH')
                                                            <div class="row g-3">
                                                                <div class="col-md-6">
                                                                    <label class="form-label">Nom <span class="text-danger">*</span></label>
                                                                    <input type="text" name="nom" class="form-control" required value="{{ $m->nom }}">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label class="form-label">Prénom</label>
                                                                    <input type="text" name="prenom" class="form-control" value="{{ $m->prenom }}">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label class="form-label">Téléphone <span class="text-danger">*</span></label>
                                                                    <input type="text" name="telephone" class="form-control" required value="{{ $m->telephone }}">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label class="form-label">E-mail</label>
                                                                    <input type="email" name="email" class="form-control" value="{{ $m->email }}">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label class="form-label">Date de naissance</label>
                                                                    <input type="date" name="date_naissance" class="form-control" value="{{ $m->date_naissance ? \Carbon\Carbon::parse($m->date_naissance)->format('Y-m-d') : '' }}">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label class="form-label">Fonction</label>
                                                                    <input type="text" name="fonction" class="form-control" value="{{ $m->fonction }}">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label class="form-label">Niveau d’étude</label>
                                                                    <select name="niveau_etude" class="form-select">
                                                                        <option value="">Non renseigné</option>
                                                                        @foreach($niveauEtudes as $n)
                                                                            <option value="{{ $n }}" @selected((string) $m->niveau_etude === (string) $n)>{{ $n }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label class="form-label">Spécialité</label>
                                                                    <input type="text" name="specialite" class="form-control" value="{{ $m->specialite }}">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label class="form-label">Site d’affectation</label>
                                                                    <select name="entreprise_site_id" class="form-select">
                                                                        <option value="">—</option>
                                                                        @foreach($item->sites as $s)
                                                                            <option value="{{ $s->id }}" @selected((int) $m->entreprise_site_id === (int) $s->id)>{{ $s->libelle }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-12">
                                                                    <label class="form-check">
                                                                        <input type="checkbox" class="form-check-input" name="associe" value="1" @checked((bool) $m->associe)>
                                                                        <span class="form-check-label">Associé</span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-12">
                                                                    <label class="form-check">
                                                                        <input type="checkbox" class="form-check-input" name="dirigeant" value="1" @checked((bool) $m->dirigeant)>
                                                                        <span class="form-check-label">Dirigeant</span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label class="form-label">Numéro CNI</label>
                                                                    <input type="text" name="cni_numero" class="form-control" value="{{ $m->cni_numero }}">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label class="form-label">Date d’expiration CNI</label>
                                                                    <input type="date" name="cni_expire_at" class="form-control" value="{{ $m->cni_expire_at ? \Carbon\Carbon::parse($m->cni_expire_at)->format('Y-m-d') : '' }}">
                                                                </div>
                                                                <div class="col-12">
                                                                    <label class="form-label">Fichier CNI (image ou PDF)</label>
                                                                    <input type="file" name="cni_fichier" class="form-control" accept=".pdf,.jpg,.jpeg,.png,application/pdf,image/*">
                                                                    @if($m->cniFichier?->path)
                                                                        <div class="form-text">Fichier actuel: <a href="{{ $m->cniFichier->path }}" target="_blank">ouvrir</a></div>
                                                                    @else
                                                                        <div class="form-text">Aucun fichier CNI actuellement.</div>
                                                                    @endif
                                                                </div>
                                                                <div class="col-12">
                                                                    <label class="form-label">Divers</label>
                                                                    <div class="summernote-wrapper summernote-wrapper--compact">
                                                                        <textarea class="form-control js-summernote-fr" name="divers">{!! $m->divers !!}</textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="mt-4 d-flex justify-content-end gap-2">
                                                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                                                                <button type="submit" class="btn btn-primary">Enregistrer</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if($siteStoreRoute)
    <div class="modal fade" id="modalAddEntrepriseSite" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter un site</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route($siteStoreRoute, $item->token) }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nom / libellé <span class="text-danger">*</span></label>
                                <input type="text" name="libelle" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Téléphone</label>
                                <input type="text" name="telephone" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">E-mail</label>
                                <input type="email" name="email" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Région</label>
                                <select id="site-filter-region" class="form-select">
                                    <option value="">Choisir...</option>
                                    @foreach($regions as $r)
                                        <option value="{{ $r->id }}">{{ $r->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Département</label>
                                <select id="site-filter-departement" class="form-select" disabled>
                                    <option value="">Choisir...</option>
                                    @foreach($departements as $d)
                                        <option value="{{ $d->id }}" data-region="{{ $d->region_id }}">{{ $d->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Commune <span class="text-danger">*</span></label>
                                <select name="arrondissement_id" id="site-arrondissement-id" class="form-select" disabled required>
                                    <option value="">Choisir...</option>
                                    @foreach($arrondissements as $a)
                                        <option value="{{ $a->id }}" data-departement="{{ $a->departement_id }}">{{ $a->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Village / quartier</label>
                                <input type="text" name="village_ou_quartier" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Latitude</label>
                                <input type="text" name="latitude" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Longitude</label>
                                <input type="text" name="longitude" class="form-control">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Divers</label>
                                <div class="summernote-wrapper summernote-wrapper--compact">
                                    <textarea class="form-control js-summernote-fr" name="divers"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif

@if($equipeStoreRoute)
    <div class="modal fade" id="modalAddEntrepriseEquipe" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter un membre</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route($equipeStoreRoute, $item->token) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nom <span class="text-danger">*</span></label>
                                <input type="text" name="nom" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Prénom</label>
                                <input type="text" name="prenom" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Téléphone <span class="text-danger">*</span></label>
                                <input type="text" name="telephone" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">E-mail</label>
                                <input type="email" name="email" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Date de naissance</label>
                                <input type="date" name="date_naissance" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Fonction</label>
                                <input type="text" name="fonction" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Niveau d’étude</label>
                                <select name="niveau_etude" class="form-select">
                                    <option value="">Non renseigné</option>
                                    @foreach($niveauEtudes as $n)
                                        <option value="{{ $n }}">{{ $n }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Spécialité</label>
                                <input type="text" name="specialite" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Site d’affectation</label>
                                <select name="entreprise_site_id" class="form-select">
                                    <option value="">—</option>
                                    @foreach($item->sites as $s)
                                        <option value="{{ $s->id }}">{{ $s->libelle }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-check">
                                    <input type="checkbox" class="form-check-input" name="associe" value="1">
                                    <span class="form-check-label">Associé</span>
                                </label>
                            </div>
                            <div class="col-12">
                                <label class="form-check">
                                    <input type="checkbox" class="form-check-input" name="dirigeant" value="1">
                                    <span class="form-check-label">Dirigeant</span>
                                </label>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Numéro CNI</label>
                                <input type="text" name="cni_numero" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Date d’expiration CNI</label>
                                <input type="date" name="cni_expire_at" class="form-control">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Fichier CNI (image ou PDF)</label>
                                <input type="file" name="cni_fichier" class="form-control" accept=".pdf,.jpg,.jpeg,.png,application/pdf,image/*">
                                <div class="form-text">Formats acceptés: PDF, JPG, JPEG, PNG. Taille max: 10 Mo.</div>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Divers</label>
                                <div class="summernote-wrapper summernote-wrapper--compact">
                                    <textarea class="form-control js-summernote-fr" name="divers"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif

@if($siteStoreRoute || $equipeStoreRoute)
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/lang/summernote-fr-FR.min.js"></script>
<script>
(function () {
    function baseOptions(height) {
        return {
            lang: 'fr-FR',
            height: height || 160,
            toolbar: [
                ['style', ['bold', 'italic', 'underline']],
                ['para', ['ul', 'ol']],
                ['insert', ['link']],
                ['view', ['codeview']]
            ]
        };
    }

    if (typeof window.jQuery !== 'undefined') {
        window.jQuery(function () {
            window.jQuery('.js-summernote-fr').each(function () {
                if (window.jQuery(this).next('.note-editor').length) return;
                window.jQuery(this).summernote(baseOptions(140));
            });
        });
    }

    const selRegion = document.getElementById('site-filter-region');
    const selDept = document.getElementById('site-filter-departement');
    const selArr = document.getElementById('site-arrondissement-id');
    if (!selRegion || !selDept || !selArr) return;

    function syncDepartements() {
        const rid = selRegion.value;
        selDept.querySelectorAll('option').forEach(function (option) {
            if (option.value === '') {
                option.hidden = false;
                return;
            }
            option.hidden = rid && option.dataset.region !== rid;
        });
        const selected = selDept.querySelector('option[value="' + selDept.value + '"]');
        if (selDept.value && selected && selected.hidden) selDept.value = '';
        selDept.disabled = !rid;
        syncArrondissements();
    }

    function syncArrondissements() {
        const did = selDept.value;
        selArr.querySelectorAll('option').forEach(function (option) {
            if (option.value === '') {
                option.hidden = false;
                return;
            }
            option.hidden = did && option.dataset.departement !== did;
        });
        const selected = selArr.querySelector('option[value="' + selArr.value + '"]');
        if (selArr.value && selected && selected.hidden) selArr.value = '';
        selArr.disabled = !did;
    }

    selRegion.addEventListener('change', function () {
        selDept.value = '';
        syncDepartements();
    });
    selDept.addEventListener('change', syncArrondissements);
    syncDepartements();
})();
</script>
@endif

