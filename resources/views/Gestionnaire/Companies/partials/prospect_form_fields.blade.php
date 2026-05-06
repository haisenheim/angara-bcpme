@php
    $item = $item ?? null;
    $showRelationshipField = $showRelationshipField ?? false;
    $relationshipFieldName = $relationshipFieldName ?? 'lien';
    $relationshipFieldLabel = $relationshipFieldLabel ?? 'Lien avec l\'entreprise principale';
    $relationshipFieldHelp = $relationshipFieldHelp ?? 'Precisez la nature du lien entre les deux entreprises.';
    $relationshipFieldValue = old($relationshipFieldName, $relationshipFieldValue ?? null);
    $relationshipSuggestions = $relationshipSuggestions ?? ['Client', 'Fournisseur', 'Banque', 'Partenaire', 'Actionnaire', 'Filiale', 'Maison mere'];
    $submitLabel = $submitLabel ?? (isset($item) ? 'Enregistrer les modifications' : 'Enregistrer le brouillon');
    $shouldUseOldInput = isset($errors) && $errors->any();

    $val = function (string $key, $default = null) use ($shouldUseOldInput, $item) {
        if ($shouldUseOldInput) {
            return old($key, $default);
        }

        if (isset($item)) {
            return $item->{$key} ?? $default;
        }

        return old($key, $default);
    };

    $dateVal = function (string $key) use ($val) {
        $value = $val($key);

        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d');
        }

        if (is_string($value) && preg_match('/^\d{4}-\d{2}-\d{2}/', $value) === 1) {
            return substr($value, 0, 10);
        }

        return $value;
    };

    $tp = $shouldUseOldInput ? old('type_personnel') : null;
    if ($tp === null && isset($item)) {
        if (! empty($item->personnel_mixte)) {
            $tp = 'mixte';
        } elseif (! empty($item->personnel_saisonier)) {
            $tp = 'saisonier';
        } elseif (! empty($item->personnel_permanent)) {
            $tp = 'permanent';
        }
    }

    $selectedProduitsSecondaires = collect($shouldUseOldInput ? old('autres', []) : (isset($item) ? $item->produits->pluck('id')->all() : []))
        ->filter()
        ->map(fn ($value) => (string) $value)
        ->values()
        ->all();

    $selectedAppuisFinanciers = collect($shouldUseOldInput ? old('appuisf', []) : (isset($item) ? $item->appuis->where('financier', 1)->pluck('id')->all() : []))
        ->filter()
        ->map(fn ($value) => (string) $value)
        ->values()
        ->all();

    $selectedAppuisNonFinanciers = collect($shouldUseOldInput ? old('appuisnf', []) : (isset($item) ? $item->appuis->where('financier', 0)->pluck('id')->all() : []))
        ->filter()
        ->map(fn ($value) => (string) $value)
        ->values()
        ->all();

    $managerPromoteurValue = $shouldUseOldInput
        ? old('manager_promoteur')
        : (isset($item) && $item->manager_promoteur !== null ? (string) (int) $item->manager_promoteur : null);

    $selectedArrondissementId = $val('arrondissement_id');
    $selectedArrondissement = filled($selectedArrondissementId)
        ? $arrondissements->firstWhere('id', (int) $selectedArrondissementId)
        : null;
    $selectedDepartementId = $selectedArrondissement?->departement_id;
    $selectedDepartement = $selectedDepartementId
        ? $departements->firstWhere('id', (int) $selectedDepartementId)
        : null;
    $selectedRegionId = $selectedDepartement?->region_id;

    $stepLabels = [
        'step-1' => 'Identite',
        'step-2' => 'Localisation',
        'step-3' => 'Dirigeant',
        'step-4' => 'Structure',
        'step-5' => 'Activites',
        'step-6' => 'Besoins',
    ];
@endphp

<div class="alert alert-danger mb-4 d-none" id="prospect-client-alert" role="alert">
    <ul class="mb-0 ps-3" id="prospect-client-alert-list"></ul>
</div>

@if($showRelationshipField)
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-lg-8">
                    <label for="{{ $relationshipFieldName }}" class="form-label">{{ $relationshipFieldLabel }} <span class="text-danger">*</span></label>
                    <input
                        type="text"
                        name="{{ $relationshipFieldName }}"
                        id="{{ $relationshipFieldName }}"
                        value="{{ $relationshipFieldValue }}"
                        class="form-control @error($relationshipFieldName) is-invalid @enderror"
                        placeholder="Ex: Fournisseur strategique, partenaire, client..."
                        list="{{ $relationshipFieldName }}-suggestions"
                        required
                    >
                    <datalist id="{{ $relationshipFieldName }}-suggestions">
                        @foreach($relationshipSuggestions as $relationshipSuggestion)
                            <option value="{{ $relationshipSuggestion }}"></option>
                        @endforeach
                    </datalist>
                    <small class="text-body-secondary">{{ $relationshipFieldHelp }}</small>
                    @error($relationshipFieldName)
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
@endif

<div class="mb-4">
    <div class="row g-2" id="prospect-stepper">
        @foreach($stepLabels as $stepId => $stepLabel)
            <div class="col-md-{{ count($stepLabels) >= 5 ? '2' : '3' }} col-6">
                <button
                    type="button"
                    class="prospect-step-trigger w-100 text-start"
                    data-step-target="{{ $stepId }}"
                >
                    <span class="prospect-step-trigger__index">{{ $loop->iteration }}</span>
                    <span class="prospect-step-trigger__content">
                        <small class="prospect-step-trigger__eyebrow">Etape {{ $loop->iteration }}</small>
                        <span class="prospect-step-trigger__title">{{ $stepLabel }}</span>
                    </span>
                </button>
            </div>
        @endforeach
    </div>
</div>

<div class="prospect-step-panels">
    <section class="prospect-step-panel" data-step-panel="step-1">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <h5 class="mb-1">Etape 1. Identite de l'entreprise</h5>
                        <p class="text-body-secondary mb-0">Denomination, references administratives et cadrage general.</p>
                    </div>
                    <span class="badge bg-light text-dark">Brouillon</span>
                </div>

                <div class="row g-3">
                    <div class="col-12">
                        <label for="name" class="form-label">Denomination <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" value="{{ $val('name') }}" class="form-control" placeholder="Raison sociale ou nom commercial">
                        <small class="text-muted">Si vide, le libelle "Prospect (a completer)" sera applique.</small>
                    </div>
                    <div class="col-md-4">
                        <label for="rccm" class="form-label">Numero RCCM</label>
                        <input
                            type="text"
                            name="rccm"
                            id="rccm"
                            value="{{ $val('rccm') }}"
                            class="form-control"
                            placeholder="Ex: RC/YAO/2024/B/123"
                            title="Format recommande RCCM Cameroun, par exemple RC/YAO/2024/B/123"
                        >
                    </div>
                    <div class="col-md-4">
                        <label for="niu" class="form-label">NIU</label>
                        <input
                            type="text"
                            name="niu"
                            id="niu"
                            value="{{ $val('niu') }}"
                            class="form-control"
                            placeholder="Ex: M123456789012A"
                            title="Format recommande NIU Cameroun, par exemple M123456789012A"
                        >
                    </div>
                    <div class="col-md-4">
                        <label for="cnps" class="form-label">Numero employeur / assurance</label>
                        <input type="text" name="cnps" id="cnps" value="{{ $val('cnps') }}" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label for="forme_id" class="form-label">Forme juridique</label>
                        <select name="forme_id" id="forme_id" class="form-select">
                            <option value="">Non renseignee</option>
                            @foreach($formes as $f)
                                <option value="{{ $f->id }}" @selected((string) $val('forme_id') === (string) $f->id)>{{ $f->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="taille" class="form-label">Taille</label>
                        <select name="taille" id="taille" class="form-select">
                            <option value="">Non renseignee</option>
                            <option value="GRANDE" @selected($val('taille') === 'GRANDE')>Grande</option>
                            <option value="MOYENNE" @selected($val('taille') === 'MOYENNE')>Moyenne</option>
                            <option value="PETITE" @selected($val('taille') === 'PETITE')>Petite</option>
                            <option value="TRES PETITE" @selected($val('taille') === 'TRES PETITE')>Tres petite</option>
                            <option value="COOPERATIVE" @selected($val('taille') === 'COOPERATIVE')>Cooperative</option>
                            <option value="ASSOCIATION" @selected($val('taille') === 'ASSOCIATION')>Association</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="caractere" class="form-label">Caractere</label>
                        <select name="caractere" id="caractere" class="form-select">
                            <option value="">Non renseigne</option>
                            <option value="Formel" @selected($val('caractere') === 'Formel')>Formel</option>
                            <option value="Informel" @selected($val('caractere') === 'Informel')>Informel</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label d-block">Systeme comptable</label>
                        <div class="choice-chip-group">
                            <label class="choice-chip">
                                <input id="systeme-normal" class="choice-chip-input" type="radio" name="systeme" value="Normal" @checked(($val('systeme') ?: 'Normal') === 'Normal')>
                                <span class="choice-chip-box">Normal</span>
                            </label>
                            <label class="choice-chip">
                                <input id="systeme-minimal" class="choice-chip-input" type="radio" name="systeme" value="Minimal" @checked($val('systeme') === 'Minimal')>
                                <span class="choice-chip-box">Minimal</span>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="mm_phone" class="form-label">Mobile Money</label>
                        <input
                            type="text"
                            name="mm_phone"
                            id="mm_phone"
                            value="{{ $val('mm_phone') }}"
                            class="form-control"
                            placeholder="Ex: 6XXXXXXXX ou +2376XXXXXXXX"
                            title="Numero Mobile Money camerounais, par exemple 6XXXXXXXX ou +2376XXXXXXXX"
                            inputmode="tel"
                        >
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end">
            <button type="button" class="wizard-nav-btn wizard-nav-btn-next" data-next-step>
                <span>Suivant</span>
                <i class="demo-pli-arrow-right ms-2"></i>
            </button>
        </div>
    </section>

    <section class="prospect-step-panel d-none" data-step-panel="step-2">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="mb-4">
                    <h5 class="mb-1">Etape 2. Localisation et contacts</h5>
                    <p class="text-body-secondary mb-0">Point de presence geographique et canaux de contact du prospect.</p>
                </div>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="filter-region" class="form-label">Region</label>
                        <select id="filter-region" class="form-select">
                            <option value="">Choisir...</option>
                            @foreach($regions as $r)
                                <option value="{{ $r->id }}" @selected((string) $selectedRegionId === (string) $r->id)>{{ $r->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="filter-departement" class="form-label">Departement</label>
                        <select id="filter-departement" class="form-select" disabled>
                            <option value="">Choisir...</option>
                            @foreach($departements as $d)
                                <option value="{{ $d->id }}" data-region="{{ $d->region_id }}" @selected((string) $selectedDepartementId === (string) $d->id)>{{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="arrondissement_id" class="form-label">Commune <span class="text-danger">*</span></label>
                        <select name="arrondissement_id" id="arrondissement_id" class="form-select @error('arrondissement_id') is-invalid @enderror" disabled>
                            <option value="">Choisir...</option>
                            @foreach($arrondissements as $a)
                                <option value="{{ $a->id }}" data-departement="{{ $a->departement_id }}" @selected((string) $val('arrondissement_id') === (string) $a->id)>{{ $a->name }}</option>
                            @endforeach
                        </select>
                        @error('arrondissement_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="phone" class="form-label">Telephone <span class="text-danger">*</span></label>
                        <input
                            type="text"
                            name="phone"
                            id="phone"
                            value="{{ $val('phone') }}"
                            class="form-control"
                            placeholder="Ex: 6XXXXXXXX, 2XXXXXXXX ou +2376XXXXXXXX"
                            title="Numero de telephone camerounais, par exemple 6XXXXXXXX, 2XXXXXXXX ou +2376XXXXXXXX"
                            inputmode="tel"
                        >
                    </div>
                    <div class="col-md-4">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" name="email" id="email" value="{{ $val('email') }}" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label for="village_ou_quartier" class="form-label">Village ou quartier</label>
                        <input type="text" name="village_ou_quartier" id="village_ou_quartier" value="{{ $val('village_ou_quartier') }}" class="form-control" placeholder="Saisir le village ou le quartier">
                    </div>
                    <div class="col-md-4">
                        <label for="latitude" class="form-label">Latitude</label>
                        <input type="text" name="latitude" id="latitude" value="{{ $val('latitude') }}" class="form-control" placeholder="Ex: 3.8480">
                    </div>
                    <div class="col-md-4">
                        <label for="longitude" class="form-label">Longitude</label>
                        <input type="text" name="longitude" id="longitude" value="{{ $val('longitude') }}" class="form-control" placeholder="Ex: 11.5021">
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between">
            <button type="button" class="wizard-nav-btn wizard-nav-btn-prev" data-prev-step>
                <i class="demo-pli-arrow-left me-2"></i>
                <span>Precedent</span>
            </button>
            <button type="button" class="wizard-nav-btn wizard-nav-btn-next" data-next-step>
                <span>Suivant</span>
                <i class="demo-pli-arrow-right ms-2"></i>
            </button>
        </div>
    </section>

    <section class="prospect-step-panel d-none" data-step-panel="step-3">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="mb-4">
                    <h5 class="mb-1">Etape 3. Profil du dirigeant</h5>
                    <p class="text-body-secondary mb-0">Informations de contact et de profil du responsable du prospect.</p>
                </div>

                <div class="row g-3">
                    <div class="col-md-8">
                        <label for="manager" class="form-label">Nom du dirigeant <span class="text-danger">*</span></label>
                        <input type="text" name="manager" id="manager" value="{{ $val('manager') }}" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label for="manager_contact" class="form-label">Contact dirigeant <span class="text-danger">*</span></label>
                        <input type="text" name="manager_contact" id="manager_contact" value="{{ $val('manager_contact') }}" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label d-block">Sexe</label>
                        <div class="choice-chip-group">
                            <label class="choice-chip">
                                <input id="manager-sexe-homme" class="choice-chip-input" type="radio" name="manager_sexe" value="Homme" @checked($val('manager_sexe') === 'Homme')>
                                <span class="choice-chip-box">Homme</span>
                            </label>
                            <label class="choice-chip">
                                <input id="manager-sexe-femme" class="choice-chip-input" type="radio" name="manager_sexe" value="Femme" @checked($val('manager_sexe') === 'Femme')>
                                <span class="choice-chip-box">Femme</span>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="manager_niveau" class="form-label">Niveau d'instruction</label>
                        <select name="manager_niveau" id="manager_niveau" class="form-select">
                            <option value="">Non renseigne</option>
                            <option value="Supérieur" @selected($val('manager_niveau') === 'Supérieur')>Superieur</option>
                            <option value="Secondaire" @selected($val('manager_niveau') === 'Secondaire')>Secondaire</option>
                            <option value="Primaire" @selected($val('manager_niveau') === 'Primaire')>Primaire</option>
                            <option value="Sans niveau" @selected($val('manager_niveau') === 'Sans niveau')>Sans niveau</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="manager_dtn" class="form-label">Date de naissance</label>
                        <input type="date" name="manager_dtn" id="manager_dtn" value="{{ $dateVal('manager_dtn') }}" class="form-control" max="{{ now()->subYears(18)->toDateString() }}">
                    </div>
                    <div class="col-md-4">
                        <label for="manager_promoteur" class="form-label">Le dirigeant est-il promoteur ?</label>
                        <select name="manager_promoteur" id="manager_promoteur" class="form-select">
                            <option value="">Non renseigne</option>
                            <option value="1" @selected($managerPromoteurValue === '1')>Oui</option>
                            <option value="0" @selected($managerPromoteurValue === '0')>Non</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between">
            <button type="button" class="wizard-nav-btn wizard-nav-btn-prev" data-prev-step>
                <i class="demo-pli-arrow-left me-2"></i>
                <span>Precedent</span>
            </button>
            <button type="button" class="wizard-nav-btn wizard-nav-btn-next" data-next-step>
                <span>Suivant</span>
                <i class="demo-pli-arrow-right ms-2"></i>
            </button>
        </div>
    </section>

    <section class="prospect-step-panel d-none" data-step-panel="step-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="mb-4">
                    <h5 class="mb-1">Etape 4. Structure et indicateurs</h5>
                    <p class="text-body-secondary mb-0">Capacite de l'entreprise, calendrier d'activite et indicateurs financiers.</p>
                </div>

                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="dt_creation" class="form-label">Date de creation formelle</label>
                        <input type="date" name="dt_creation" id="dt_creation" value="{{ $dateVal('dt_creation') }}" class="form-control" max="{{ now()->toDateString() }}">
                    </div>
                    <div class="col-md-3">
                        <label for="dt_start" class="form-label">Debut des activites</label>
                        <input type="date" name="dt_start" id="dt_start" value="{{ $dateVal('dt_start') }}" class="form-control" max="{{ now()->toDateString() }}">
                    </div>
                    <div class="col-md-3">
                        <label for="capital" class="form-label">Capital social</label>
                        <input type="number" step="0.01" name="capital" id="capital" value="{{ $val('capital') }}" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label for="chiffre_affaire" class="form-label">Chiffre d'affaire</label>
                        <input type="number" step="0.01" name="chiffre_affaire" id="chiffre_affaire" value="{{ $val('chiffre_affaire') }}" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label for="ressources_propres" class="form-label">Ressources propres</label>
                        <input type="number" step="0.01" name="ressources_propres" id="ressources_propres" value="{{ $val('ressources_propres') }}" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label for="total_actif" class="form-label">Total actif</label>
                        <input type="number" step="0.01" name="total_actif" id="total_actif" value="{{ $val('total_actif') }}" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label for="nb_personnel" class="form-label">Nombre total de personnes</label>
                        <input type="number" name="nb_personnel" id="nb_personnel" value="{{ $val('nb_personnel') }}" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label for="nb_personnel_permanent" class="form-label">Personnel permanent</label>
                        <input type="number" name="nb_personnel_permanent" id="nb_personnel_permanent" value="{{ $val('nb_personnel_permanent') }}" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label for="nb_personnel_saisonier" class="form-label">Personnel saisonnier</label>
                        <input type="number" name="nb_personnel_saisonier" id="nb_personnel_saisonier" value="{{ $val('nb_personnel_saisonier') }}" class="form-control">
                    </div>
                    <div class="col-12">
                        <label class="form-label d-block">Type de personnel dominant</label>
                        <div class="choice-chip-group">
                            <label class="choice-chip">
                                <input id="type-permanent" class="choice-chip-input" type="radio" name="type_personnel" value="permanent" @checked($tp === 'permanent')>
                                <span class="choice-chip-box">Permanent</span>
                            </label>
                            <label class="choice-chip">
                                <input id="type-saisonier" class="choice-chip-input" type="radio" name="type_personnel" value="saisonier" @checked($tp === 'saisonier')>
                                <span class="choice-chip-box">Saisonnier</span>
                            </label>
                            <label class="choice-chip">
                                <input id="type-mixte" class="choice-chip-input" type="radio" name="type_personnel" value="mixte" @checked($tp === 'mixte')>
                                <span class="choice-chip-box">Mixte</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between">
            <button type="button" class="wizard-nav-btn wizard-nav-btn-prev" data-prev-step>
                <i class="demo-pli-arrow-left me-2"></i>
                <span>Precedent</span>
            </button>
            <button type="button" class="wizard-nav-btn wizard-nav-btn-next" data-next-step>
                <span>Suivant</span>
                <i class="demo-pli-arrow-right ms-2"></i>
            </button>
        </div>
    </section>

    <section class="prospect-step-panel d-none" data-step-panel="step-5">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="mb-4">
                    <h5 class="mb-1">Etape 5. Activites</h5>
                    <p class="text-body-secondary mb-0">Produits ou services actuellement portes par le prospect.</p>
                </div>

                <div class="row g-3">
                    <div class="col-md-8">
                        <label for="produit_id" class="form-label">Produit principal</label>
                        <select id="produit_id" name="produit_id" class="form-select">
                            <option value="">Non renseigne</option>
                            @foreach($produitsCatalogue as $produit)
                                <option value="{{ $produit->id }}" @selected((string) $val('produit_id') === (string) $produit->id)>
                                    {{ trim(($produit->code ? $produit->code . ' ' : '') . $produit->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="produit_year_start" class="form-label">Anciennete sur le produit principal</label>
                        <input name="produit_year_start" id="produit_year_start" value="{{ $val('produit_year_start') }}" class="form-control" type="number" min="0" placeholder="Nombre d'annees">
                    </div>
                    <div class="col-md-4">
                        <label for="autres" class="form-label">Produits secondaires</label>
                        <details class="multi-check-dropdown" data-preview-target="autres-preview" data-label-target="autres-label">
                            <summary class="form-select">
                                <span id="autres-label">Selectionner les produits secondaires</span>
                            </summary>
                            <div class="multi-check-dropdown__menu border rounded shadow-sm bg-white p-2">
                                @foreach($produitsCatalogue as $produit)
                                    <label class="check-option">
                                        <input
                                            class="check-option-input multi-check-dropdown__checkbox"
                                            type="checkbox"
                                            name="autres[]"
                                            value="{{ $produit->id }}"
                                            data-label="{{ trim(($produit->code ? $produit->code . ' ' : '') . $produit->name) }}"
                                            @checked(in_array((string) $produit->id, $selectedProduitsSecondaires, true))
                                        >
                                        <span class="check-option-box">{{ trim(($produit->code ? $produit->code . ' ' : '') . $produit->name) }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </details>
                        <div class="mt-2">
                            <small class="text-body-secondary d-block mb-1">Selection actuelle</small>
                            <div class="multi-check-dropdown-preview">
                                <div id="autres-preview" class="d-flex flex-wrap gap-2"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between">
            <button type="button" class="wizard-nav-btn wizard-nav-btn-prev" data-prev-step>
                <i class="demo-pli-arrow-left me-2"></i>
                <span>Precedent</span>
            </button>
            <button type="button" class="wizard-nav-btn wizard-nav-btn-next" data-next-step>
                <span>Suivant</span>
                <i class="demo-pli-arrow-right ms-2"></i>
            </button>
        </div>
    </section>

    <section class="prospect-step-panel d-none" data-step-panel="step-6">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="mb-4">
                    <h5 class="mb-1">Etape 6. Besoins</h5>
                    <p class="text-body-secondary mb-0">Appuis souhaites pour accompagner le developpement du prospect.</p>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="appuisf" class="form-label">Appuis financiers souhaites</label>
                        <details class="multi-check-dropdown" data-preview-target="appuisf-preview" data-label-target="appuisf-label">
                            <summary class="form-select">
                                <span id="appuisf-label">Selectionner les appuis financiers</span>
                            </summary>
                            <div class="multi-check-dropdown__menu border rounded shadow-sm bg-white p-2">
                                @foreach($appuisFinanciers as $service)
                                    <label class="check-option">
                                        <input
                                            class="check-option-input multi-check-dropdown__checkbox"
                                            type="checkbox"
                                            name="appuisf[]"
                                            value="{{ $service->id }}"
                                            data-label="{{ $service->name }}@if($service->type) ({{ $service->type->name }}) @endif"
                                            @checked(in_array((string) $service->id, $selectedAppuisFinanciers, true))
                                        >
                                        <span class="check-option-box">{{ $service->name }}@if($service->type) ({{ $service->type->name }}) @endif</span>
                                    </label>
                                @endforeach
                            </div>
                        </details>
                        <div class="mt-2">
                            <small class="text-body-secondary d-block mb-1">Selection actuelle</small>
                            <div class="multi-check-dropdown-preview">
                                <div id="appuisf-preview" class="d-flex flex-wrap gap-2"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="appuisnf" class="form-label">Appuis non financiers souhaites</label>
                        <details class="multi-check-dropdown" data-preview-target="appuisnf-preview" data-label-target="appuisnf-label">
                            <summary class="form-select">
                                <span id="appuisnf-label">Selectionner les appuis non financiers</span>
                            </summary>
                            <div class="multi-check-dropdown__menu border rounded shadow-sm bg-white p-2">
                                @foreach($appuisNonFinanciers as $service)
                                    <label class="check-option">
                                        <input
                                            class="check-option-input multi-check-dropdown__checkbox"
                                            type="checkbox"
                                            name="appuisnf[]"
                                            value="{{ $service->id }}"
                                            data-label="{{ $service->name }}@if($service->type) ({{ $service->type->name }}) @endif"
                                            @checked(in_array((string) $service->id, $selectedAppuisNonFinanciers, true))
                                        >
                                        <span class="check-option-box">{{ $service->name }}@if($service->type) ({{ $service->type->name }}) @endif</span>
                                    </label>
                                @endforeach
                            </div>
                        </details>
                        <div class="mt-2">
                            <small class="text-body-secondary d-block mb-1">Selection actuelle</small>
                            <div class="multi-check-dropdown-preview">
                                <div id="appuisnf-preview" class="d-flex flex-wrap gap-2"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between">
            <button type="button" class="wizard-nav-btn wizard-nav-btn-prev" data-prev-step>
                <i class="demo-pli-arrow-left me-2"></i>
                <span>Precedent</span>
            </button>
            <div class="text-end">
                <button type="submit" class="wizard-nav-btn wizard-nav-btn-next">
                    <i class="demo-psi-add me-2"></i>{{ $submitLabel }}
                </button>
            </div>
        </div>
    </section>
</div>

<style>
#prospect-stepper {
    --accent-color: #88b824;
    --accent-color-dark: #6f9a1d;
    --accent-soft: rgba(136, 184, 36, 0.10);
    --accent-soft-strong: rgba(136, 184, 36, 0.18);
    --stepper-border: #d9e2ec;
    --stepper-bg: #ffffff;
}

.prospect-step-trigger {
    display: flex;
    align-items: center;
    gap: 0.55rem;
    min-height: 54px;
    width: 100%;
    padding: 0.45rem 0.65rem;
    border: 1px solid var(--stepper-border);
    border-radius: 0.65rem;
    background: var(--stepper-bg);
    color: #334155;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
    transition: all 0.2s ease;
}

.prospect-step-trigger:hover {
    border-color: #b8c7d9;
    background: #f8fafc;
    box-shadow: 0 2px 8px rgba(15, 23, 42, 0.06);
}

.prospect-step-trigger__index {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 1.45rem;
    height: 1.45rem;
    border-radius: 999px;
    background: #eef2f6;
    color: #475569;
    font-size: 0.75rem;
    font-weight: 700;
    flex: 0 0 auto;
}

.prospect-step-trigger__content {
    display: flex;
    flex-direction: column;
    min-width: 0;
}

.prospect-step-trigger__eyebrow {
    display: block;
    margin-bottom: 0.05rem;
    color: #64748b;
    font-size: 0.62rem;
    letter-spacing: 0.03em;
    text-transform: uppercase;
}

.prospect-step-trigger__title {
    display: block;
    font-weight: 600;
    font-size: 0.88rem;
    color: #1e293b;
    line-height: 1.2;
}

.prospect-step-trigger.is-active {
    border-color: var(--accent-color);
    background: rgba(136, 184, 36, 0.08);
    color: #0f172a;
    box-shadow: inset 0 0 0 1px var(--accent-soft);
}

.prospect-step-trigger.is-active .prospect-step-trigger__index {
    background: var(--accent-color);
    color: #fff;
}

.prospect-step-trigger.is-active .prospect-step-trigger__eyebrow,
.prospect-step-trigger.is-active .prospect-step-trigger__title {
    color: #0f172a;
}

.choice-chip-group {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
}

.choice-chip {
    position: relative;
    margin: 0;
    cursor: pointer;
}

.choice-chip-input {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}

.choice-chip-box {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 120px;
    padding: 0.75rem 1rem;
    border: 1px solid #d8dee6;
    border-radius: 0.9rem;
    background: #fff;
    color: #334155;
    font-weight: 500;
    transition: all 0.2s ease;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
}

.choice-chip:hover .choice-chip-box {
    border-color: #a8cb5f;
    box-shadow: 0 4px 14px rgba(136, 184, 36, 0.10);
}

.choice-chip-input:checked + .choice-chip-box {
    border-color: var(--accent-color);
    background: var(--accent-soft);
    color: #4e6d14;
    box-shadow: 0 0 0 0.2rem rgba(136, 184, 36, 0.14);
}

.choice-chip-input:focus-visible + .choice-chip-box {
    outline: 2px solid rgba(136, 184, 36, 0.35);
    outline-offset: 2px;
}

.prospect-step-panels .btn-primary {
    background-color: var(--accent-color);
    border-color: var(--accent-color);
}

.prospect-step-panels .btn-primary:hover,
.prospect-step-panels .btn-primary:focus,
.prospect-step-panels .btn-primary:active {
    background-color: var(--accent-color-dark);
    border-color: var(--accent-color-dark);
}

.wizard-nav-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 38px;
    padding: 0.52rem 0.9rem;
    border-radius: 0.65rem;
    border: 1px solid transparent;
    font-weight: 600;
    font-size: 0.92rem;
    line-height: 1;
    transition: all 0.2s ease;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.05);
    cursor: pointer;
    text-decoration: none;
    appearance: none;
    -webkit-appearance: none;
}

.wizard-nav-btn-next {
    min-width: 118px;
    background: #88b824 !important;
    border-color: #88b824 !important;
    color: #fff !important;
    box-shadow: 0 4px 12px rgba(136, 184, 36, 0.22);
}

.wizard-nav-btn-next:hover,
.wizard-nav-btn-next:focus,
.wizard-nav-btn-next:active {
    background: #6f9a1d !important;
    border-color: #6f9a1d !important;
    color: #fff !important;
    box-shadow: 0 6px 16px rgba(136, 184, 36, 0.28);
}

.wizard-nav-btn-prev {
    min-width: 112px;
    background: #fff !important;
    border-color: #d9e2ec !important;
    color: #475569 !important;
}

.wizard-nav-btn-prev:hover,
.wizard-nav-btn-prev:focus,
.wizard-nav-btn-prev:active {
    background: #f8fafc !important;
    border-color: #c7d2df !important;
    color: #1f2937 !important;
    box-shadow: 0 4px 12px rgba(15, 23, 42, 0.08);
}

.multi-check-dropdown {
    position: relative;
    z-index: 1;
}

.multi-check-dropdown[open] {
    z-index: 30;
}

.multi-check-dropdown summary {
    list-style: none;
    cursor: pointer;
}

.multi-check-dropdown summary::-webkit-details-marker {
    display: none;
}

/* Liste deroulante hors flux : n'etire pas la carte / le wizard a l'ouverture */
.multi-check-dropdown__menu {
    position: absolute;
    left: 0;
    right: 0;
    top: calc(100% + 0.35rem);
    max-height: 260px;
    overflow-y: auto;
    z-index: 31;
    -webkit-overflow-scrolling: touch;
}

/* Previsualisation des choix : hauteur fixe, scroll interne si debordement (le flex est sur l'enfant, pas sur la zone scroll) */
.multi-check-dropdown-preview {
    box-sizing: border-box;
    height: 7.5rem;
    max-height: 7.5rem;
    overflow-y: auto;
    overflow-x: hidden;
    padding: 0.45rem 0.5rem;
    border: 1px solid #e2e8f0;
    border-radius: 0.65rem;
    background: #f8fafc;
}

.check-option {
    display: block;
    margin-bottom: 0.5rem;
    cursor: pointer;
}

.check-option:last-child {
    margin-bottom: 0;
}

.check-option-input {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}

.check-option-box {
    display: block;
    padding: 0.7rem 0.85rem;
    border: 1px solid #e2e8f0;
    border-radius: 0.85rem;
    background: #fff;
    color: #334155;
    transition: all 0.2s ease;
}

.check-option:hover .check-option-box {
    border-color: #a8cb5f;
    background: #fafcf4;
}

.check-option-input:checked + .check-option-box {
    border-color: var(--accent-color);
    background: var(--accent-soft);
    color: #4e6d14;
    box-shadow: inset 0 0 0 1px var(--accent-soft-strong);
}

.check-option-input:focus-visible + .check-option-box {
    outline: 2px solid rgba(136, 184, 36, 0.35);
    outline-offset: 2px;
}

.selection-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.35rem 0.55rem;
    border-radius: 999px;
    background: var(--accent-soft);
    border: 1px solid var(--accent-soft-strong);
    color: #4e6d14;
    font-size: 0.78rem;
    font-weight: 600;
    line-height: 1;
    cursor: pointer;
    user-select: none;
    -webkit-appearance: none;
    appearance: none;
    text-decoration: none;
}

.selection-badge:hover {
    filter: brightness(0.98);
}

.selection-badge:focus-visible {
    outline: 2px solid rgba(136, 184, 36, 0.35);
    outline-offset: 2px;
}

.selection-badge__remove {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 1rem;
    height: 1rem;
    border-radius: 999px;
    border: 1px solid rgba(78, 109, 20, 0.25);
    background: rgba(255, 255, 255, 0.7);
    color: #4e6d14;
    font-size: 0.85rem;
    font-weight: 800;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const stepTriggers = Array.from(document.querySelectorAll('.prospect-step-trigger'));
    const stepPanels = Array.from(document.querySelectorAll('.prospect-step-panel'));
    const stepOrder = stepPanels.map((panel) => panel.dataset.stepPanel);
    let currentStepIndex = 0;

    function showStep(stepId) {
        currentStepIndex = Math.max(stepOrder.indexOf(stepId), 0);

        stepPanels.forEach((panel) => {
            panel.classList.toggle('d-none', panel.dataset.stepPanel !== stepId);
        });

        stepTriggers.forEach((trigger) => {
            const isActive = trigger.dataset.stepTarget === stepId;
            trigger.classList.toggle('is-active', isActive);
        });

        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    stepTriggers.forEach((trigger) => {
        trigger.addEventListener('click', function () {
            showStep(this.dataset.stepTarget);
        });
    });

    document.querySelectorAll('[data-next-step]').forEach((button) => {
        button.addEventListener('click', function () {
            if (currentStepIndex < stepOrder.length - 1) {
                showStep(stepOrder[currentStepIndex + 1]);
            }
        });
    });

    document.querySelectorAll('[data-prev-step]').forEach((button) => {
        button.addEventListener('click', function () {
            if (currentStepIndex > 0) {
                showStep(stepOrder[currentStepIndex - 1]);
            }
        });
    });

    function renderCheckboxDropdown(dropdown) {
        const previewId = dropdown.dataset.previewTarget;
        const labelId = dropdown.dataset.labelTarget;
        const preview = previewId ? document.getElementById(previewId) : null;
        const label = labelId ? document.getElementById(labelId) : null;
        if (!preview && !label) {
            return;
        }

        const selectedOptions = Array.from(dropdown.querySelectorAll('.multi-check-dropdown__checkbox:checked'));
        if (selectedOptions.length === 0) {
            if (preview) {
                preview.innerHTML = '<span class="badge bg-light text-dark border">Aucune selection</span>';
            }
            if (label) {
                label.textContent = 'Aucune selection';
            }
            return;
        }

        function escapeHtml(value) {
            return String(value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        const labels = selectedOptions.map((option) => option.dataset.label.trim());
        if (preview) {
            preview.innerHTML = selectedOptions
                .map((option) => {
                    const text = option.dataset.label.trim();
                    const value = option.value;
                    return (
                        '<button type="button" class="selection-badge" data-remove-value="' +
                        escapeHtml(value) +
                        '" aria-label="Retirer ' +
                        escapeHtml(text) +
                        '">' +
                        '<span>' +
                        escapeHtml(text) +
                        '</span>' +
                        '<span class="selection-badge__remove" aria-hidden="true">&times;</span>' +
                        '</button>'
                    );
                })
                .join('');
        }
        if (label) {
            label.textContent = labels.length === 1 ? labels[0] : labels.length + ' elements selectionnes';
        }
    }

    document.querySelectorAll('.multi-check-dropdown').forEach((dropdown) => {
        renderCheckboxDropdown(dropdown);
        dropdown.querySelectorAll('.multi-check-dropdown__checkbox').forEach((checkbox) => {
            checkbox.addEventListener('change', function () {
                renderCheckboxDropdown(dropdown);
            });
        });

        const previewId = dropdown.dataset.previewTarget;
        const preview = previewId ? document.getElementById(previewId) : null;
        if (preview) {
            preview.addEventListener('click', function (event) {
                const btn = event.target && event.target.closest ? event.target.closest('[data-remove-value]') : null;
                if (!btn) {
                    return;
                }

                event.preventDefault();
                event.stopPropagation();

                const value = btn.getAttribute('data-remove-value');
                const cssEscape = function (val) {
                    if (window.CSS && typeof window.CSS.escape === 'function') {
                        return window.CSS.escape(val);
                    }
                    return String(val).replace(/["\\]/g, '\\$&');
                };
                const checkbox = dropdown.querySelector('.multi-check-dropdown__checkbox[value="' + cssEscape(value) + '"]');
                if (checkbox) {
                    checkbox.checked = false;
                    checkbox.dispatchEvent(new Event('change', { bubbles: true }));
                }
            });
        }

        dropdown.addEventListener('toggle', function () {
            if (!dropdown.open) {
                return;
            }
            document.querySelectorAll('.multi-check-dropdown').forEach((other) => {
                if (other !== dropdown) {
                    other.removeAttribute('open');
                }
            });
        });
    });

    const form = document.getElementById('form-prospect') || document.getElementById('form-prospect-edit');
    const clientAlert = document.getElementById('prospect-client-alert');
    const clientAlertList = document.getElementById('prospect-client-alert-list');

    function getFieldValue(fieldId) {
        const field = document.getElementById(fieldId);
        return field ? field.value.trim() : '';
    }

    function resetClientAlert() {
        if (!clientAlert || !clientAlertList) {
            return;
        }

        clientAlert.classList.add('d-none');
        clientAlertList.innerHTML = '';
    }

    function showClientAlert(errors, firstFieldId) {
        if (!clientAlert || !clientAlertList || errors.length === 0) {
            return;
        }

        clientAlertList.innerHTML = errors.map((error) => '<li>' + error + '</li>').join('');
        clientAlert.classList.remove('d-none');

        if (firstFieldId) {
            const field = document.getElementById(firstFieldId);
            const panel = field ? field.closest('.prospect-step-panel') : null;
            if (panel) {
                showStep(panel.dataset.stepPanel);
            }
            if (field) {
                field.focus();
            }
        }

        clientAlert.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function validateClientFormats() {
        const errors = [];
        let firstFieldId = null;

        const validations = [
            {
                fieldId: 'rccm',
                regex: /^RC\/[A-Z0-9-]+\/\d{4}\/[A-Z0-9]+\/\d+$/i,
                message: 'Le RCCM doit respecter un format camerounais valide, par exemple RC/YAO/2024/B/123.',
            },
            {
                fieldId: 'niu',
                regex: /^[A-Z][A-Z0-9]{10,19}$/i,
                message: 'Le NIU doit respecter un format camerounais valide, par exemple M123456789012A.',
            },
            {
                fieldId: 'phone',
                regex: /^(?:\+237)?(?:2|6)\d{8}$/,
                message: 'Le telephone doit suivre la numerotation camerounaise, par exemple 6XXXXXXXX, 2XXXXXXXX ou +2376XXXXXXXX.',
            },
            {
                fieldId: 'mm_phone',
                regex: /^(?:\+237)?6\d{8}$/,
                message: 'Le numero Mobile Money doit suivre la numerotation camerounaise mobile, par exemple 6XXXXXXXX ou +2376XXXXXXXX.',
            },
        ];

        validations.forEach((rule) => {
            const value = getFieldValue(rule.fieldId);
            if (value !== '' && !rule.regex.test(value.replace(/[\s.-]/g, ''))) {
                errors.push(rule.message);
                if (!firstFieldId) {
                    firstFieldId = rule.fieldId;
                }
            }
        });

        return { errors, firstFieldId };
    }

    function parseDateValue(fieldId) {
        const value = getFieldValue(fieldId);
        if (value === '') {
            return null;
        }

        const parsed = new Date(value + 'T00:00:00');
        return Number.isNaN(parsed.getTime()) ? null : parsed;
    }

    function validateClientDates() {
        const errors = [];
        let firstFieldId = null;
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        const adultLimit = new Date(today);
        adultLimit.setFullYear(adultLimit.getFullYear() - 18);

        const managerBirthDate = parseDateValue('manager_dtn');
        const creationDate = parseDateValue('dt_creation');
        const startDate = parseDateValue('dt_start');

        if (managerBirthDate && managerBirthDate > adultLimit) {
            errors.push('La date de naissance du dirigeant doit etre coherente: le dirigeant doit etre majeur.');
            firstFieldId = firstFieldId || 'manager_dtn';
        }

        if (creationDate && creationDate > today) {
            errors.push('La date de creation formelle ne peut pas etre dans le futur.');
            firstFieldId = firstFieldId || 'dt_creation';
        }

        if (startDate && startDate > today) {
            errors.push('La date de debut des activites ne peut pas etre dans le futur.');
            firstFieldId = firstFieldId || 'dt_start';
        }

        if (managerBirthDate && creationDate && creationDate <= managerBirthDate) {
            errors.push('La date de creation formelle doit etre posterieure a la date de naissance du dirigeant.');
            firstFieldId = firstFieldId || 'dt_creation';
        }

        if (managerBirthDate && startDate && startDate <= managerBirthDate) {
            errors.push('La date de debut des activites doit etre posterieure a la date de naissance du dirigeant.');
            firstFieldId = firstFieldId || 'dt_start';
        }

        return { errors, firstFieldId };
    }

    if (form) {
        form.addEventListener('submit', function (event) {
            resetClientAlert();

            const formatValidation = validateClientFormats();
            const dateValidation = validateClientDates();
            const errors = [...formatValidation.errors, ...dateValidation.errors];
            const firstFieldId = formatValidation.firstFieldId || dateValidation.firstFieldId;

            if (errors.length > 0) {
                event.preventDefault();
                showClientAlert(errors, firstFieldId);
            }
        });
    }

    showStep(stepOrder[0]);

    const selRegion = document.getElementById('filter-region');
    const selDept = document.getElementById('filter-departement');
    const selArr = document.getElementById('arrondissement_id');
    if (!selRegion || !selDept || !selArr) {
        return;
    }

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
        if (selDept.value && selected && selected.hidden) {
            selDept.value = '';
        }

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
        if (selArr.value && selected && selected.hidden) {
            selArr.value = '';
        }

        selArr.disabled = !did;
    }

    selRegion.addEventListener('change', function () {
        selDept.value = '';
        syncDepartements();
    });

    selDept.addEventListener('change', syncArrondissements);

    syncDepartements();

    @if(isset($item) && $item->arrondissement_id)
        (function () {
            const opt = selArr.querySelector('option[value="{{ $item->arrondissement_id }}"]');
            if (!opt) {
                return;
            }

            const departementId = opt.dataset.departement;
            const departementOpt = selDept.querySelector('option[value="' + departementId + '"]');
            if (departementOpt && departementOpt.dataset.region) {
                selRegion.value = departementOpt.dataset.region;
                syncDepartements();
                selDept.value = departementId;
                syncArrondissements();
                selArr.value = '{{ $item->arrondissement_id }}';
            }
        })();
    @elseif(old('arrondissement_id'))
        (function () {
            const opt = selArr.querySelector('option[value="{{ old('arrondissement_id') }}"]');
            if (!opt) {
                return;
            }

            const departementId = opt.dataset.departement;
            const departementOpt = selDept.querySelector('option[value="' + departementId + '"]');
            if (departementOpt && departementOpt.dataset.region) {
                selRegion.value = departementOpt.dataset.region;
                syncDepartements();
                selDept.value = departementId;
                syncArrondissements();
                selArr.value = '{{ old('arrondissement_id') }}';
            }
        })();
    @endif
});
</script>
