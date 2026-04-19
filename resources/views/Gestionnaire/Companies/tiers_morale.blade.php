@extends('Layouts.gestionnaire')

@push('styles')
<style>
.tier-mode-switch {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 1rem;
    padding: 0.35rem;
    border-radius: 1.25rem;
    background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
    border: 1px solid #e2e8f0;
}

.tier-mode-option {
    position: relative;
}

.tier-mode-option input {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}

.tier-mode-card {
    position: relative;
    height: 100%;
    display: flex;
    flex-direction: column;
    gap: 0.45rem;
    min-height: 132px;
    padding: 1.05rem 1.1rem 1rem;
    border: 1px solid transparent;
    border-radius: 1rem;
    background: rgba(255, 255, 255, 0.82);
    cursor: pointer;
    overflow: hidden;
    transition: all 0.22s ease;
}

.tier-mode-card::before {
    content: '';
    position: absolute;
    inset: 0 auto auto 0;
    width: 100%;
    height: 4px;
    background: transparent;
    transition: background 0.22s ease;
}

.tier-mode-card::after {
    content: '';
    position: absolute;
    top: 1rem;
    right: 1rem;
    width: 0.95rem;
    height: 0.95rem;
    border: 2px solid #cbd5e1;
    border-radius: 999px;
    background: #fff;
    box-shadow: inset 0 0 0 3px #fff;
    transition: all 0.2s ease;
}

.tier-mode-option:hover .tier-mode-card {
    transform: translateY(-1px);
    border-color: #cbd5e1;
    background: #fff;
    box-shadow: 0 10px 24px rgba(15, 23, 42, 0.05);
}

.tier-mode-option input:focus + .tier-mode-card {
    outline: none;
    border-color: rgba(136, 184, 36, 0.42);
    box-shadow: 0 0 0 0.25rem rgba(136, 184, 36, 0.16);
}

.tier-mode-option input:checked + .tier-mode-card {
    transform: translateY(-1px);
    border-color: rgba(136, 184, 36, 0.42);
    background: linear-gradient(180deg, rgba(136, 184, 36, 0.14), rgba(255, 255, 255, 0.96));
    box-shadow: 0 14px 28px rgba(15, 23, 42, 0.08);
}

.tier-mode-option input:checked + .tier-mode-card::before {
    background: linear-gradient(90deg, #88b824, #6f9a1d);
}

.tier-mode-option input:checked + .tier-mode-card::after {
    border-color: #88b824;
    background: #88b824;
    box-shadow: inset 0 0 0 3px #fff;
}

.tier-mode-title {
    display: block;
    margin-bottom: 0.1rem;
    font-weight: 700;
    color: #0f172a;
    font-size: 1rem;
    line-height: 1.35;
}

.tier-mode-card .text-body-secondary {
    max-width: 34rem;
    font-size: 0.92rem !important;
    line-height: 1.5;
}

@media (max-width: 767.98px) {
    .tier-mode-switch {
        grid-template-columns: 1fr;
    }

    .tier-mode-card {
        min-height: 116px;
    }
}

.tier-search-results {
    display: grid;
    gap: 0.75rem;
}

.tier-search-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #e5e7eb;
}

.tier-search-hint {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-top: 0.75rem;
}

.tier-search-chip {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.4rem 0.75rem;
    border: 1px solid #d9e2ec;
    border-radius: 999px;
    background: #fff;
    color: #334155;
    font-size: 0.84rem;
    cursor: pointer;
    transition: all 0.2s ease;
}

.tier-search-chip:hover,
.tier-search-chip:focus {
    border-color: rgba(136, 184, 36, 0.42);
    background: rgba(136, 184, 36, 0.08);
    color: #17310b;
}

.tier-search-result {
    padding: 0.95rem 1rem;
    border: 1px solid #d9e2ec;
    border-radius: 0.9rem;
    background: #fff;
    cursor: pointer;
    transition: all 0.2s ease;
}

.tier-search-result:hover,
.tier-search-result.is-selected {
    border-color: rgba(136, 184, 36, 0.42);
    background: rgba(136, 184, 36, 0.08);
}

.tier-search-result__title {
    font-weight: 700;
    color: #0f172a;
}

.tier-search-result__meta {
    color: #64748b;
    font-size: 0.9rem;
}

.tier-search-result__grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 0.75rem 1rem;
    margin-top: 0.9rem;
}

.tier-search-result__label {
    display: block;
    margin-bottom: 0.15rem;
    color: #64748b;
    font-size: 0.76rem;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}

.tier-search-result__value {
    color: #0f172a;
    font-size: 0.92rem;
    word-break: break-word;
}

.tier-search-result__footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
    margin-top: 0.9rem;
    padding-top: 0.85rem;
    border-top: 1px dashed #d9e2ec;
}

.tier-search-status {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.3rem 0.65rem;
    border-radius: 999px;
    font-size: 0.78rem;
    font-weight: 700;
}

.tier-search-status--prospect {
    background: rgba(245, 158, 11, 0.16);
    color: #92400e;
}

.tier-search-status--client {
    background: rgba(22, 163, 74, 0.14);
    color: #166534;
}

.tier-selected-company {
    border: 1px solid rgba(136, 184, 36, 0.28);
    border-radius: 1rem;
    background: linear-gradient(135deg, rgba(136, 184, 36, 0.12), rgba(255, 255, 255, 0.98));
}

.tier-selected-company__meta {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 0.75rem 1rem;
    margin-top: 1rem;
}

.tier-selected-company__actions {
    display: flex;
    flex-wrap: wrap;
    gap: 0.65rem;
    margin-top: 1rem;
}

.tier-search-empty {
    padding: 1.1rem;
    border: 1px dashed #d9e2ec;
    border-radius: 0.95rem;
    background: #f8fafc;
    color: #64748b;
}

@media (max-width: 767.98px) {
    .tier-mode-switch,
    .tier-search-result__grid,
    .tier-selected-company__meta {
        grid-template-columns: 1fr;
    }

    .tier-search-toolbar,
    .tier-search-result__footer {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>
@endpush

@section('title', 'Nouveau tiers personne morale')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('gestionnaire.dashboard') }}">Angara</a></li>
        <li class="breadcrumb-item"><a href="{{ route('gestionnaire.entreprises.index') }}">Entreprises</a></li>
        <li class="breadcrumb-item"><a href="{{ route('gestionnaire.entreprises.show', $parentEntreprise->token) }}">{{ Str::limit($parentEntreprise->name, 40) }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">Nouveau tiers personne morale</li>
    </ol>
</nav>
@endsection

@section('actions')
    <a href="{{ route('gestionnaire.entreprises.show', $parentEntreprise->token) }}" class="btn btn-outline-secondary btn-sm"><i class="demo-pli-arrow-left me-2"></i>Retour à la fiche</a>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Nouveau tiers personne morale</h5>
        <p class="text-body-secondary mb-0">Vous pouvez lier un prospect ou client deja present dans le portefeuille global de la banque, ou creer un nouveau prospect si aucune fiche n'existe encore.</p>
    </div>
@endsection

@section('content')
    @php
        $tierMode = old('tier_mode', 'existing');
    @endphp
    <div class="row justify-content-center">
        <div class="col-12 col-xl-11">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-lg-5">
                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="post" action="{{ route('gestionnaire.entreprise.morale.save') }}" id="form-tier-morale" novalidate>
                        @csrf
                        <input type="hidden" name="entreprise_id" value="{{ $parentEntreprise->id }}">
                        <input type="hidden" name="token" value="{{ $parentEntreprise->token }}">
                        <input type="hidden" name="selected_company_id" id="selected_company_id" value="{{ old('selected_company_id') }}">
                        <input type="hidden" name="selected_company_name" id="selected_company_name_input" value="{{ old('selected_company_name') }}">
                        <input type="hidden" name="selected_company_status" id="selected_company_status_input" value="{{ old('selected_company_status') }}">
                        <input type="hidden" name="selected_company_email" id="selected_company_email_input" value="{{ old('selected_company_email') }}">
                        <input type="hidden" name="selected_company_phone" id="selected_company_phone_input" value="{{ old('selected_company_phone') }}">
                        <input type="hidden" name="selected_company_manager" id="selected_company_manager_input" value="{{ old('selected_company_manager') }}">
                        <input type="hidden" name="selected_company_show_url" id="selected_company_show_url_input" value="{{ old('selected_company_show_url') }}">

                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
                                    <div>
                                        <p class="text-uppercase text-muted small fw-semibold mb-2">Antidoublon</p>
                                        <h5 class="mb-1">Comment souhaitez-vous rattacher ce tiers ?</h5>
                                        <p class="text-body-secondary mb-0">Commencez de preference par une recherche dans le portefeuille global pour eviter de recreer un prospect ou un client deja connu.</p>
                                    </div>
                                    <span class="badge bg-light text-dark border">Entreprise source : {{ $parentEntreprise->name }}</span>
                                </div>

                                <div class="tier-mode-switch">
                                    <label class="tier-mode-option">
                                        <input type="radio" name="tier_mode" value="existing" @checked($tierMode === 'existing')>
                                        <span class="tier-mode-card">
                                            <span class="tier-mode-title">Lier une fiche existante</span>
                                            <span class="text-body-secondary small">Rechercher un prospect ou un client deja present dans le portefeuille global de la banque.</span>
                                        </span>
                                    </label>
                                    <label class="tier-mode-option">
                                        <input type="radio" name="tier_mode" value="new" @checked($tierMode === 'new')>
                                        <span class="tier-mode-card">
                                            <span class="tier-mode-title">Creer un nouveau prospect</span>
                                            <span class="text-body-secondary small">Utilisez cette option uniquement si aucune fiche existante n'a ete retrouvee.</span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-lg-6">
                                        <label for="lien" class="form-label">Nature du lien <span class="text-danger">*</span></label>
                                        <input
                                            type="text"
                                            name="lien"
                                            id="lien"
                                            value="{{ old('lien') }}"
                                            class="form-control @error('lien') is-invalid @enderror"
                                            placeholder="Ex: Fournisseur, partenaire, filiale, client..."
                                            list="lien-suggestions"
                                            required
                                        >
                                        <datalist id="lien-suggestions">
                                            <option value="Client"></option>
                                            <option value="Fournisseur"></option>
                                            <option value="Banque"></option>
                                            <option value="Partenaire"></option>
                                            <option value="Actionnaire"></option>
                                            <option value="Filiale"></option>
                                            <option value="Maison mere"></option>
                                        </datalist>
                                        @error('lien')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="commentaire" class="form-label">Commentaire</label>
                                        <textarea
                                            name="commentaire"
                                            id="commentaire"
                                            rows="3"
                                            class="form-control @error('commentaire') is-invalid @enderror"
                                            placeholder="Commentaire complementaire sur la relation entre les deux entreprises"
                                        >{{ old('commentaire') }}</textarea>
                                        @error('commentaire')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="tier-mode-existing" @class(['d-none' => $tierMode !== 'existing'])>
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
                                        <div>
                                            <h5 class="mb-1">Recherche dans le portefeuille global</h5>
                                            <p class="text-body-secondary mb-0">Recherchez par denomination, RCCM, NIU, email, telephone ou nom du dirigeant.</p>
                                        </div>
                                    </div>

                                    <div class="row g-3">
                                        <div class="col-lg-8">
                                            <label for="tier-search-input" class="form-label">Rechercher une fiche existante</label>
                                            <input type="search" id="tier-search-input" class="form-control" placeholder="Ex: nom, RCCM, NIU, email, telephone..." autocomplete="off">
                                            <small class="text-body-secondary">Les resultats incluent prospects et clients du portefeuille global de la banque.</small>
                                            <div class="tier-search-hint">
                                                <button type="button" class="tier-search-chip" data-search-chip="RCCM">
                                                    <i class="demo-psi-search-people"></i>Recherche par RCCM
                                                </button>
                                                <button type="button" class="tier-search-chip" data-search-chip="NIU">
                                                    <i class="demo-psi-search-people"></i>Recherche par NIU
                                                </button>
                                                <button type="button" class="tier-search-chip" data-search-chip="Telephone">
                                                    <i class="demo-psi-search-people"></i>Recherche par telephone
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    @error('selected_company_id')
                                        <div class="alert alert-danger mt-3 mb-0">{{ $message }}</div>
                                    @enderror

                                    <div id="tier-selected-company" class="tier-selected-company p-3 mt-4 {{ old('selected_company_id') ? '' : 'd-none' }}">
                                        <div class="d-flex justify-content-between align-items-start gap-3">
                                            <div>
                                                <p class="text-uppercase text-muted small fw-semibold mb-2">Fiche selectionnee</p>
                                                <h6 class="mb-1" id="tier-selected-company-name">{{ old('selected_company_name') ?: 'Entreprise selectionnee' }}</h6>
                                                <p class="text-body-secondary mb-1" id="tier-selected-company-status">{{ old('selected_company_status') }}</p>
                                                <div class="small text-body-secondary" id="tier-selected-company-meta">
                                                    {{ trim(collect([old('selected_company_email'), old('selected_company_phone'), old('selected_company_manager')])->filter()->implode(' | ')) }}
                                                </div>
                                                <div class="tier-selected-company__meta">
                                                    <div>
                                                        <span class="text-muted small d-block">Email</span>
                                                        <strong id="tier-selected-company-email">{{ old('selected_company_email') ?: '—' }}</strong>
                                                    </div>
                                                    <div>
                                                        <span class="text-muted small d-block">Telephone</span>
                                                        <strong id="tier-selected-company-phone">{{ old('selected_company_phone') ?: '—' }}</strong>
                                                    </div>
                                                    <div>
                                                        <span class="text-muted small d-block">Dirigeant</span>
                                                        <strong id="tier-selected-company-manager">{{ old('selected_company_manager') ?: '—' }}</strong>
                                                    </div>
                                                    <div>
                                                        <span class="text-muted small d-block">Statut</span>
                                                        <strong id="tier-selected-company-type">{{ old('selected_company_status') ?: '—' }}</strong>
                                                    </div>
                                                </div>
                                                <div class="tier-selected-company__actions">
                                                    <a href="{{ old('selected_company_show_url') ?: '#' }}" class="btn btn-sm btn-light border {{ old('selected_company_show_url') ? '' : 'd-none' }}" id="tier-selected-company-link" target="_blank" rel="noopener">
                                                        Ouvrir la fiche
                                                    </a>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-light border" id="tier-clear-selection">Changer</button>
                                        </div>
                                    </div>

                                    <div class="tier-search-toolbar">
                                        <div id="tier-search-state" class="text-body-secondary small">Saisissez au moins 2 caracteres pour lancer la recherche.</div>
                                        <div class="small text-body-secondary" id="tier-search-count"></div>
                                    </div>

                                    <div class="mt-3">
                                        <div id="tier-search-results" class="tier-search-results mt-3"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="demo-psi-link me-2"></i>Lier la fiche existante
                                </button>
                            </div>
                        </div>

                        <div id="tier-mode-new" @class(['d-none' => $tierMode !== 'new'])>
                            @include('Gestionnaire.Companies.partials.prospect_form_fields', [
                                'item' => null,
                                'showRelationshipField' => false,
                                'submitLabel' => 'Creer puis lier le tiers personne morale',
                            ])
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    jQuery(function ($) {
        const modeInputs = $('input[name="tier_mode"]');
        const existingPanel = $('#tier-mode-existing');
        const newPanel = $('#tier-mode-new');
        const searchInput = $('#tier-search-input');
        const searchState = $('#tier-search-state');
        const searchCount = $('#tier-search-count');
        const resultsContainer = $('#tier-search-results');
        const selectedCompanyInput = $('#selected_company_id');
        const selectedCompanyBox = $('#tier-selected-company');
        const clearSelectionBtn = $('#tier-clear-selection');
        const selectedName = $('#tier-selected-company-name');
        const selectedStatus = $('#tier-selected-company-status');
        const selectedMeta = $('#tier-selected-company-meta');
        const selectedEmail = $('#tier-selected-company-email');
        const selectedPhone = $('#tier-selected-company-phone');
        const selectedManager = $('#tier-selected-company-manager');
        const selectedType = $('#tier-selected-company-type');
        const selectedLink = $('#tier-selected-company-link');
        const selectedNameInput = $('#selected_company_name_input');
        const selectedStatusInput = $('#selected_company_status_input');
        const selectedEmailInput = $('#selected_company_email_input');
        const selectedPhoneInput = $('#selected_company_phone_input');
        const selectedManagerInput = $('#selected_company_manager_input');
        const selectedShowUrlInput = $('#selected_company_show_url_input');
        const form = $('#form-tier-morale');
        let searchTimer = null;

        function syncModePanels() {
            const mode = modeInputs.filter(':checked').val();
            const existingActive = mode === 'existing';

            existingPanel.toggleClass('d-none', !existingActive);
            newPanel.toggleClass('d-none', existingActive);

            existingPanel.find('input, textarea, select, button').prop('disabled', !existingActive);
            newPanel.find('input, textarea, select, button').prop('disabled', existingActive);

            $('input[name="tier_mode"]').prop('disabled', false);
            $('input[name="entreprise_id"], input[name="token"], input[name="selected_company_id"], input[name="lien"], textarea[name="commentaire"]').prop('disabled', false);
        }

        function escapeHtml(value) {
            return String(value || '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function renderSelectedCompany(company) {
            if (!company) {
                selectedCompanyBox.addClass('d-none');
                selectedName.text('Entreprise selectionnee');
                selectedStatus.text('');
                selectedMeta.text('');
                selectedEmail.text('—');
                selectedPhone.text('—');
                selectedManager.text('—');
                selectedType.text('—');
                selectedLink.addClass('d-none').attr('href', '#');
                selectedNameInput.val('');
                selectedStatusInput.val('');
                selectedEmailInput.val('');
                selectedPhoneInput.val('');
                selectedManagerInput.val('');
                selectedShowUrlInput.val('');
                return;
            }

            selectedCompanyBox.removeClass('d-none');
            selectedName.text(company.name || 'Denomination non renseignee');
            selectedStatus.text(company.status_label || '');
            selectedMeta.text([company.email, company.phone, company.manager].filter(Boolean).join(' | '));
            selectedEmail.text(company.email || '—');
            selectedPhone.text(company.phone || '—');
            selectedManager.text(company.manager || '—');
            selectedType.text(company.status_label || '—');
            selectedNameInput.val(company.name || '');
            selectedStatusInput.val(company.status_label || '');
            selectedEmailInput.val(company.email || '');
            selectedPhoneInput.val(company.phone || '');
            selectedManagerInput.val(company.manager || '');
            selectedShowUrlInput.val(company.show_url || '');

            if (company.show_url) {
                selectedLink.removeClass('d-none').attr('href', company.show_url);
            } else {
                selectedLink.addClass('d-none').attr('href', '#');
            }
        }

        function clearSelection() {
            selectedCompanyInput.val('');
            renderSelectedCompany(null);
            resultsContainer.find('.tier-search-result').removeClass('is-selected');
            searchCount.text('');
        }

        function renderResults(items) {
            if (!items.length) {
                resultsContainer.html('<div class="tier-search-empty">Aucun resultat n a ete trouve. Essayez avec un RCCM, un NIU, un email, un telephone ou le nom du dirigeant.</div>');
                searchState.text('Aucune fiche correspondante trouvee dans le portefeuille global.');
                searchCount.text('0 resultat');
                return;
            }

            const selectedId = selectedCompanyInput.val();
            searchCount.text(items.length + (items.length > 1 ? ' resultats' : ' resultat'));
            resultsContainer.html(items.map(function (item) {
                const statusClass = item.prospect ? 'tier-search-status tier-search-status--prospect' : 'tier-search-status tier-search-status--client';

                return `
                    <button type="button" class="tier-search-result text-start ${selectedId && String(selectedId) === String(item.id) ? 'is-selected' : ''}" data-company='${JSON.stringify(item).replace(/'/g, '&#039;')}'>
                        <div class="d-flex justify-content-between align-items-start gap-3">
                            <div>
                                <div class="tier-search-result__title">${escapeHtml(item.name)}</div>
                                <div class="tier-search-result__meta">${escapeHtml(item.representation || 'Portefeuille global')}</div>
                            </div>
                            <span class="${statusClass}">${item.prospect ? 'Prospect' : 'Client'}</span>
                        </div>
                        <div class="tier-search-result__grid">
                            <div>
                                <span class="tier-search-result__label">RCCM</span>
                                <div class="tier-search-result__value">${escapeHtml(item.rccm || '—')}</div>
                            </div>
                            <div>
                                <span class="tier-search-result__label">NIU</span>
                                <div class="tier-search-result__value">${escapeHtml(item.niu || '—')}</div>
                            </div>
                            <div>
                                <span class="tier-search-result__label">Telephone</span>
                                <div class="tier-search-result__value">${escapeHtml(item.phone || '—')}</div>
                            </div>
                            <div>
                                <span class="tier-search-result__label">Email</span>
                                <div class="tier-search-result__value">${escapeHtml(item.email || '—')}</div>
                            </div>
                            <div>
                                <span class="tier-search-result__label">Dirigeant</span>
                                <div class="tier-search-result__value">${escapeHtml(item.manager || '—')}</div>
                            </div>
                            <div>
                                <span class="tier-search-result__label">Agence / Representation</span>
                                <div class="tier-search-result__value">${escapeHtml([item.agence, item.representation].filter(Boolean).join(' / ') || '—')}</div>
                            </div>
                        </div>
                        <div class="tier-search-result__footer">
                            <span class="tier-search-result__meta">Cliquez pour selectionner cette fiche et lier la relation.</span>
                            ${item.show_url ? `<span class="small text-body-secondary">Fiche disponible</span>` : ''}
                        </div>
                    </button>
                `;
            }).join(''));
        }

        function searchPortfolio(term) {
            const query = String(term || '').trim();

            if (query.length < 2) {
                resultsContainer.html('');
                searchState.text('Saisissez au moins 2 caracteres pour lancer la recherche.');
                searchCount.text('');
                return;
            }

            searchState.text('Recherche en cours...');
            searchCount.text('');

            $.get("{{ route('gestionnaire.entreprise.morale.search') }}", {
                q: query,
                exclude_id: "{{ $parentEntreprise->id }}"
            }).done(function (response) {
                renderResults(response.data || []);
                if ((response.data || []).length > 0) {
                    searchState.text('Selectionnez une fiche existante a lier.');
                }
            }).fail(function () {
                resultsContainer.html('');
                searchState.text('Impossible de charger les resultats pour le moment.');
                searchCount.text('');
            });
        }

        modeInputs.on('change', syncModePanels);

        $('[data-search-chip]').on('click', function () {
            const value = $(this).data('search-chip');
            searchInput.val(value).trigger('input').trigger('focus');
        });

        searchInput.on('input', function () {
            const value = $(this).val();
            window.clearTimeout(searchTimer);
            searchTimer = window.setTimeout(function () {
                searchPortfolio(value);
            }, 250);
        });

        resultsContainer.on('click', '.tier-search-result', function () {
            const company = $(this).data('company');
            selectedCompanyInput.val(company.id);
            renderSelectedCompany(company);
            resultsContainer.find('.tier-search-result').removeClass('is-selected');
            $(this).addClass('is-selected');
        });

        clearSelectionBtn.on('click', function () {
            clearSelection();
            searchInput.trigger('focus');
        });

        form.on('submit', function (event) {
            if (modeInputs.filter(':checked').val() === 'existing' && !selectedCompanyInput.val()) {
                event.preventDefault();
                searchState.text('Veuillez d abord selectionner un prospect ou un client existant.');
                searchInput.trigger('focus');
            }
        });

        syncModePanels();
    });
</script>
@endsection
