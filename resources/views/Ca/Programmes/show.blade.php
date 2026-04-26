@php $rp = $routePrefix ?? 'ca'; @endphp
@extends($layout ?? 'Layouts.ca')

@section('title', $item->name)
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="{{ route($rp.'.dashboard') }}">Tableau de bord</a></li>
       <li class="breadcrumb-item"><a href="{{ route($rp.'.programmes.index') }}">Programmes</a></li>
       <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($item->name, 40) }}</li>
    </ol>
</nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">{{ $item->name }}</h5>
        <p class="text-body-secondary mb-0 mt-1">Fiche signalétique du programme</p>
    </div>
@endsection

@section('content')
    <div class="row g-4">
        {{-- Panneau d'informations --}}
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="mb-0 fw-semibold text-body">
                        <i class="demo-psi-file-edit me-2 text-primary"></i>Informations générales
                    </h6>
                </div>
                <div class="card-body">
                    <dl class="row g-3 mb-0 programme-info-list">
                        <div class="col-12">
                            <dt class="text-muted small text-uppercase mb-1">Désignation</dt>
                            <dd class="mb-0 fw-medium">{{ $item->name }}</dd>
                        </div>
                        <div class="col-12">
                            <dt class="text-muted small text-uppercase mb-1">N° Référence convention cadre</dt>
                            <dd class="mb-0">{{ $item->convention ?? '—' }}</dd>
                        </div>
                        <div class="col-12">
                            <dt class="text-muted small text-uppercase mb-1">Date de signature</dt>
                            <dd class="mb-0">{{ $item->dt_sig_conv ? \Carbon\Carbon::parse($item->dt_sig_conv)->format('d/m/Y') : '—' }}</dd>
                        </div>
                        <div class="col-12">
                            <dt class="text-muted small text-uppercase mb-1">Institution signataire</dt>
                            <dd class="mb-0">{{ $item->signataire ?? '—' }}</dd>
                        </div>
                        <div class="col-12 pt-2 border-top">
                            <dt class="text-muted small text-uppercase mb-2">Budgets</dt>
                            <dd class="mb-0">
                                <div class="d-flex flex-column gap-2">
                                    <div class="d-flex justify-content-between"><span class="text-muted">Appuis financiers</span><span class="fw-medium">{{ number_format($item->budget_af ?? 0, 0, ',', '.') }} XAF</span></div>
                                    <div class="d-flex justify-content-between"><span class="text-muted">Appuis non financiers</span><span class="fw-medium">{{ number_format($item->budget_anf ?? 0, 0, ',', '.') }} XAF</span></div>
                                    <div class="d-flex justify-content-between"><span class="text-muted">Coordination</span><span class="fw-medium">{{ number_format($item->budget_coord ?? 0, 0, ',', '.') }} XAF</span></div>
                                    <div class="d-flex justify-content-between pt-2 border-top"><span class="fw-semibold">Total</span><span class="fw-bold text-primary">{{ number_format($item->budget ?? 0, 0, ',', '.') }} XAF</span></div>
                                </div>
                            </dd>
                        </div>
                        <div class="col-12">
                            <dt class="text-muted small text-uppercase mb-1">Bénéficiaires cibles PM</dt>
                            <dd class="mb-0">{{ $item->type_pm ?? '—' }}</dd>
                        </div>
                        <div class="col-12">
                            <dt class="text-muted small text-uppercase mb-1">Bénéficiaires cibles PP</dt>
                            <dd class="mb-0">{{ $item->type_pp ?? '—' }}</dd>
                        </div>
                        <div class="col-12">
                            <dt class="text-muted small text-uppercase mb-1">Début des activités</dt>
                            <dd class="mb-0">{{ $item->dt_start ? \Carbon\Carbon::parse($item->dt_start)->format('d/m/Y') : '—' }}</dd>
                        </div>
                        <div class="col-12">
                            <dt class="text-muted small text-uppercase mb-1">Contact</dt>
                            <dd class="mb-0">{{ $item->contact ?? '—' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        {{-- Contenu principal avec onglets --}}
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <ul class="nav nav-underline nav-component border-bottom px-3 pt-2" role="tablist">
                        <li class="nav-item" role="presentation"><button class="nav-link px-3 active" data-bs-toggle="tab" data-bs-target="#_tab1" type="button" role="tab">Secteurs cibles</button></li>
                        <li class="nav-item" role="presentation"><button class="nav-link px-3" data-bs-toggle="tab" data-bs-target="#_tab2" type="button" role="tab">Appuis proposés</button></li>
                        <li class="nav-item" role="presentation"><button class="nav-link px-3" data-bs-toggle="tab" data-bs-target="#_tab3" type="button" role="tab">Composantes</button></li>
                        <li class="nav-item" role="presentation"><button class="nav-link px-3" data-bs-toggle="tab" data-bs-target="#_tab4" type="button" role="tab">Résultats attendus</button></li>
                        <li class="nav-item" role="presentation"><button class="nav-link px-3" data-bs-toggle="tab" data-bs-target="#_tab5" type="button" role="tab">Entreprises</button></li>
                    </ul>
                    <div class="tab-content p-4">
                        <div id="_tab1" class="tab-pane fade active show">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr><th>Produit</th><th>Filière</th><th>Branche</th></tr>
                                    </thead>
                                    <tbody>
                                        @forelse($item->produits as $p)
                                            <tr><td>{{ $p->name }}</td><td>{{ $p->filiere?->name ?? '—' }}</td><td>{{ $p->branche?->name ?? '—' }}</td></tr>
                                        @empty
                                            <tr><td colspan="3" class="text-center text-muted py-4">Aucun secteur cible</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div id="_tab2" class="tab-pane fade">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr><th>Service</th><th>Type</th><th>Nature</th></tr>
                                    </thead>
                                    <tbody>
                                        @forelse($item->appuis as $service)
                                            <tr><td>{{ $service->name }}</td><td>{{ $service->type?->name ?? '—' }}</td><td><span class="badge bg-{{ $service->financier ? 'success' : 'info' }} bg-opacity-10 text-{{ $service->financier ? 'success' : 'info' }}">{{ $service->financier ? 'Financier' : 'Non financier' }}</span></td></tr>
                                        @empty
                                            <tr><td colspan="3" class="text-center text-muted py-4">Aucun appui proposé</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div id="_tab3" class="tab-pane fade">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr><th>Entité</th><th>Nature</th></tr>
                                    </thead>
                                    <tbody>
                                        @forelse($item->composantes as $cmp)
                                            <tr><td>{{ $cmp->name }}</td><td>{{ $cmp->type ?? '—' }}</td></tr>
                                        @empty
                                            <tr><td colspan="2" class="text-center text-muted py-4">Aucune composante</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div id="_tab4" class="tab-pane fade">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr><th>Indicateur</th><th>Attentes</th></tr>
                                    </thead>
                                    <tbody>
                                        @forelse($item->resultats as $r)
                                            <tr><td>{{ $r->indicateur?->name ?? '—' }}</td><td>{{ $r->attente ?? '—' }}</td></tr>
                                        @empty
                                            <tr><td colspan="2" class="text-center text-muted py-4">Aucun résultat attendu</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div id="_tab5" class="tab-pane fade">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr><th>Entreprise</th><th>Localité</th><th>Taille</th></tr>
                                    </thead>
                                    <tbody>
                                        @forelse($item->entreprises as $ent)
                                            <tr><td>{{ $ent->name }}</td><td>{{ $ent->localite ?? '—' }}</td><td>{{ $ent->taille ?? '—' }}</td></tr>
                                        @empty
                                            <tr><td colspan="3" class="text-center text-muted py-4">Aucune entreprise</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(!empty($instructionBudgetConsumption))
            @php
                $ibc = $instructionBudgetConsumption;
                $bAf = (float) ($item->budget_af ?? 0);
                $bAnf = (float) ($item->budget_anf ?? 0);
                $bCoord = (float) ($item->budget_coord ?? 0);
                $eFin = (float) $ibc['engaged_financier'];
                $eNf = (float) $ibc['engaged_non_financier'];
                $eCoord = 0.0;
                $bTotalProg = $bAf + $bAnf + $bCoord;
                $eTotalEng = $eFin + $eNf;
                $pctFinRaw = $bAf > 0 ? round($eFin / $bAf * 100, 1) : ($eFin > 0 ? 100.0 : 0.0);
                $pctNfRaw = $bAnf > 0 ? round($eNf / $bAnf * 100, 1) : ($eNf > 0 ? 100.0 : 0.0);
                $pctCoordRaw = $bCoord > 0 ? round($eCoord / $bCoord * 100, 1) : 0.0;
                $pctTotalRaw = $bTotalProg > 0 ? round($eTotalEng / $bTotalProg * 100, 1) : ($eTotalEng > 0 ? 100.0 : 0.0);
                $pctFinBar = min(100, max(0, $pctFinRaw));
                $pctNfBar = min(100, max(0, $pctNfRaw));
                $finAppuis = $item->appuis->where('financier', true);
                $nfAppuis = $item->appuis->where('financier', false);
                $nFinCat = $finAppuis->count();
                $nNfCat = $nfAppuis->count();
                $shareFinEnv = $nFinCat > 0 ? $bAf / $nFinCat : $bAf;
                $shareFinEng = $nFinCat > 0 ? $eFin / $nFinCat : $eFin;
                $shareNfEnv = $nNfCat > 0 ? $bAnf / $nNfCat : $bAnf;
                $shareNfEng = $nNfCat > 0 ? $eNf / $nNfCat : $eNf;
            @endphp
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <h6 class="mb-1 fw-semibold text-body">
                            <i class="demo-psi-calculator me-2 text-primary"></i>Consommation des budgets d’appui
                        </h6>
                        <p class="text-body-secondary small mb-0">
                            Montants affectés dans les dossiers d’instruction <strong>déjà validés par le chef d’agence</strong>
                            (budgets financier et non financier saisis par le chef de filière pour ce programme).
                        </p>
                    </div>
                    <div class="card-body pt-0">
                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-6">
                                <div class="border rounded-3 p-3 h-100">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <span class="fw-semibold">Appuis financiers</span>
                                        @if($eFin > $bAf && $bAf > 0)
                                            <span class="badge text-bg-warning">Dépasse l’enveloppe</span>
                                        @endif
                                    </div>
                                    <div class="small text-muted mb-1">Enveloppe convention&nbsp;: <span class="text-body">{{ number_format($bAf, 0, ',', '.') }} XAF</span></div>
                                    <div class="small text-muted mb-2">Engagé sur dossiers validés agence&nbsp;: <span class="fw-medium text-body">{{ number_format($eFin, 0, ',', '.') }} XAF</span></div>
                                    <div class="d-flex align-items-baseline justify-content-between mt-2 mb-1">
                                        <span class="small text-muted">Taux de consommation</span>
                                        <span class="fs-5 fw-bold {{ $pctFinRaw > 100 ? 'text-warning' : 'text-primary' }}">{{ number_format($pctFinRaw, 1, ',', ' ') }}&nbsp;%</span>
                                    </div>
                                    <div class="progress" style="height: 0.5rem;">
                                        <div class="progress-bar {{ $eFin > $bAf && $bAf > 0 ? 'bg-warning' : 'bg-primary' }}" role="progressbar" style="width: {{ $pctFinBar }}%;" aria-valuenow="{{ $pctFinBar }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div class="small text-muted mt-2 mb-0">
                                        Reste indicatif&nbsp;: {{ number_format(max(0, $bAf - $eFin), 0, ',', '.') }} XAF
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="border rounded-3 p-3 h-100">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <span class="fw-semibold">Appuis non financiers</span>
                                        @if($eNf > $bAnf && $bAnf > 0)
                                            <span class="badge text-bg-warning">Dépasse l’enveloppe</span>
                                        @endif
                                    </div>
                                    <div class="small text-muted mb-1">Enveloppe convention&nbsp;: <span class="text-body">{{ number_format($bAnf, 0, ',', '.') }} XAF</span></div>
                                    <div class="small text-muted mb-2">Engagé sur dossiers validés agence&nbsp;: <span class="fw-medium text-body">{{ number_format($eNf, 0, ',', '.') }} XAF</span></div>
                                    <div class="d-flex align-items-baseline justify-content-between mt-2 mb-1">
                                        <span class="small text-muted">Taux de consommation</span>
                                        <span class="fs-5 fw-bold {{ $pctNfRaw > 100 ? 'text-warning' : 'text-info' }}">{{ number_format($pctNfRaw, 1, ',', ' ') }}&nbsp;%</span>
                                    </div>
                                    <div class="progress" style="height: 0.5rem;">
                                        <div class="progress-bar {{ $eNf > $bAnf && $bAnf > 0 ? 'bg-warning' : 'bg-info' }}" role="progressbar" style="width: {{ $pctNfBar }}%;" aria-valuenow="{{ $pctNfBar }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div class="small text-muted mt-2 mb-0">
                                        Reste indicatif&nbsp;: {{ number_format(max(0, $bAnf - $eNf), 0, ',', '.') }} XAF
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="alert alert-light border mb-0 py-2 small">
                                    <strong>Coordination.</strong> Enveloppe programme&nbsp;: {{ number_format($bCoord, 0, ',', '.') }} XAF —
                                    taux de consommation suivi&nbsp;: <strong>{{ number_format($pctCoordRaw, 1, ',', ' ') }}&nbsp;%</strong>
                                    (aucune saisie au niveau dossier pour cette ligne) —
                                    la ventilation par dossier porte sur les appuis financier / non financier ci-dessus.
                                </div>
                            </div>
                        </div>

                        <h6 class="fw-semibold small text-uppercase text-muted mb-2">Synthèse des taux de consommation</h6>
                        <div class="table-responsive mb-4">
                            <table class="table table-sm table-bordered align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Poste</th>
                                        <th class="text-end">Enveloppe (XAF)</th>
                                        <th class="text-end">Engagé dossiers validés (XAF)</th>
                                        <th class="text-end">Taux</th>
                                        <th class="text-end">Reste indicatif (XAF)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Appuis financiers</td>
                                        <td class="text-end text-nowrap">{{ number_format($bAf, 0, ',', '.') }}</td>
                                        <td class="text-end text-nowrap">{{ number_format($eFin, 0, ',', '.') }}</td>
                                        <td class="text-end fw-semibold {{ $pctFinRaw > 100 ? 'text-warning' : '' }}">{{ number_format($pctFinRaw, 1, ',', ' ') }}&nbsp;%</td>
                                        <td class="text-end text-nowrap">{{ number_format(max(0, $bAf - $eFin), 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Appuis non financiers</td>
                                        <td class="text-end text-nowrap">{{ number_format($bAnf, 0, ',', '.') }}</td>
                                        <td class="text-end text-nowrap">{{ number_format($eNf, 0, ',', '.') }}</td>
                                        <td class="text-end fw-semibold {{ $pctNfRaw > 100 ? 'text-warning' : '' }}">{{ number_format($pctNfRaw, 1, ',', ' ') }}&nbsp;%</td>
                                        <td class="text-end text-nowrap">{{ number_format(max(0, $bAnf - $eNf), 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Coordination</td>
                                        <td class="text-end text-nowrap">{{ number_format($bCoord, 0, ',', '.') }}</td>
                                        <td class="text-end text-nowrap">{{ number_format($eCoord, 0, ',', '.') }}</td>
                                        <td class="text-end fw-semibold">{{ number_format($pctCoordRaw, 1, ',', ' ') }}&nbsp;%</td>
                                        <td class="text-end text-nowrap">{{ number_format(max(0, $bCoord - $eCoord), 0, ',', '.') }}</td>
                                    </tr>
                                    <tr class="table-light">
                                        <td class="fw-semibold">Total budgets programme</td>
                                        <td class="text-end text-nowrap fw-semibold">{{ number_format($bTotalProg, 0, ',', '.') }}</td>
                                        <td class="text-end text-nowrap fw-semibold">{{ number_format($eTotalEng, 0, ',', '.') }}</td>
                                        <td class="text-end fw-bold {{ $pctTotalRaw > 100 ? 'text-warning' : 'text-body' }}">{{ number_format($pctTotalRaw, 1, ',', ' ') }}&nbsp;%</td>
                                        <td class="text-end text-nowrap fw-semibold">{{ number_format(max(0, $bTotalProg - $eTotalEng), 0, ',', '.') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <p class="small text-muted mt-2 mb-0">
                                Le <strong>taux total</strong> rapporte les engagements dossier (financier + non financier) à la somme des enveloppes conventionnelles, coordination incluse.
                                Un taux supérieur à 100&nbsp;% signale un dépassement sur au moins une enveloppe.
                            </p>
                        </div>

                        @if($item->appuis->isNotEmpty())
                            <h6 class="fw-semibold small text-uppercase text-muted mb-2">Appuis prévus au catalogue — ventilation indicative et taux par nature</h6>
                            <p class="small text-body-secondary mb-2">
                                En l’absence de sous-enveloppes par appui en base, chaque ligne du même type reçoit une <strong>part égale</strong> de l’enveloppe et des montants engagés de sa nature&nbsp;;
                                le <strong>taux de consommation</strong> est celui de la nature (identique pour tous les appuis financiers, resp. non financiers).
                            </p>
                            <div class="table-responsive mb-4">
                                <table class="table table-sm table-bordered align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Service / appui</th>
                                            <th>Type</th>
                                            <th>Nature</th>
                                            <th class="text-end">Enveloppe ind. (XAF)</th>
                                            <th class="text-end">Engagé ind. (XAF)</th>
                                            <th class="text-end">Taux nature</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($item->appuis as $service)
                                            @php
                                                $isFin = (bool) $service->financier;
                                                $envInd = $isFin ? $shareFinEnv : $shareNfEnv;
                                                $engInd = $isFin ? $shareFinEng : $shareNfEng;
                                                $pctNat = $isFin ? $pctFinRaw : $pctNfRaw;
                                            @endphp
                                            <tr>
                                                <td>{{ $service->name }}</td>
                                                <td>{{ $service->type?->name ?? '—' }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $isFin ? 'success' : 'info' }} bg-opacity-10 text-{{ $isFin ? 'success' : 'info' }}">
                                                        {{ $isFin ? 'Financier' : 'Non financier' }}
                                                    </span>
                                                </td>
                                                <td class="text-end text-nowrap">{{ number_format($envInd, 0, ',', '.') }}</td>
                                                <td class="text-end text-nowrap">{{ number_format($engInd, 0, ',', '.') }}</td>
                                                <td class="text-end fw-semibold {{ $pctNat > 100 ? 'text-warning' : '' }}">{{ number_format($pctNat, 1, ',', ' ') }}&nbsp;%</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        <h6 class="fw-semibold small text-uppercase text-muted mb-2">Détail par dossier (validation chef d’agence)</h6>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Validation agence</th>
                                        <th>Client</th>
                                        <th>Agence</th>
                                        <th class="text-end">Appui fin.</th>
                                        <th class="text-end">% env. fin</th>
                                        <th class="text-end">Appui non fin.</th>
                                        <th class="text-end">% env. non fin.</th>
                                        <th class="text-end">Sous-total</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($ibc['lignes'] as $lig)
                                        @php
                                            $st = $lig->budget_appui_financier + $lig->budget_appui_non_financier;
                                            $pctLigneFin = $bAf > 0 ? round($lig->budget_appui_financier / $bAf * 100, 2) : ($lig->budget_appui_financier > 0 ? 100.0 : 0.0);
                                            $pctLigneNf = $bAnf > 0 ? round($lig->budget_appui_non_financier / $bAnf * 100, 2) : ($lig->budget_appui_non_financier > 0 ? 100.0 : 0.0);
                                        @endphp
                                        <tr>
                                            <td class="text-nowrap small">
                                                {{ $lig->instruction_agence_validated_at ? \Carbon\Carbon::parse($lig->instruction_agence_validated_at)->format('d/m/Y H:i') : '—' }}
                                            </td>
                                            <td>
                                                @if($lig->entreprise_token)
                                                    <a href="{{ route($rp.'.entreprises.show', $lig->entreprise_token) }}">{{ $lig->entreprise_name ?? '—' }}</a>
                                                @else
                                                    {{ $lig->entreprise_name ?? '—' }}
                                                @endif
                                            </td>
                                            <td class="small">{{ $lig->agence_name ?? '—' }}</td>
                                            <td class="text-end text-nowrap">{{ number_format($lig->budget_appui_financier, 0, ',', '.') }}</td>
                                            <td class="text-end text-nowrap small">{{ number_format($pctLigneFin, 2, ',', ' ') }}&nbsp;%</td>
                                            <td class="text-end text-nowrap">{{ number_format($lig->budget_appui_non_financier, 0, ',', '.') }}</td>
                                            <td class="text-end text-nowrap small">{{ number_format($pctLigneNf, 2, ',', ' ') }}&nbsp;%</td>
                                            <td class="text-end text-nowrap fw-medium">{{ number_format($st, 0, ',', '.') }}</td>
                                            <td class="text-end">
                                                <a href="{{ route($rp.'.dossiers.show', $lig->dossier_token) }}" class="btn btn-sm btn-outline-primary">Dossier</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center text-muted py-4">Aucune affectation enregistrée pour ce programme sur des dossiers validés par le chef d’agence.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <p class="small text-muted mt-2 mb-0">
                                <strong>% env. fin / non fin.</strong> : part de l’enveloppe programme de la nature correspondante représentée par la ligne dossier (somme des lignes peut dépasser 100&nbsp;% en cas de dépassement d’enveloppe).
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
