@php
    $isEdit = $item->exists;
    $organisationType = old('organisation_type', $item->organisation_type ?: ((int) ($item->agence_id ?? 0) > 0 ? 'agence' : 'entite'));
    $directionLabel = old('agence_id', $item->agence_id)
        ? ($item->agence?->representation?->name ?? "Direction determinee par l'agence selectionnee")
        : "La direction sera deduite de l'agence selectionnee";
@endphp

@csrf
@if($isEdit)
    @method('PUT')
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label for="name" class="form-label">Nom complet</label>
                <input id="name" type="text" name="name" class="form-control" value="{{ old('name', $item->name) }}" required>
            </div>
            <div class="col-md-3">
                <label for="phone" class="form-label">Telephone</label>
                <input id="phone" type="text" name="phone" class="form-control" value="{{ old('phone', $item->phone) }}" required>
            </div>
            <div class="col-md-3">
                <label for="role_id" class="form-label">Role</label>
                <select id="role_id" name="role_id" class="form-control" required>
                    <option value="">Selectionner un role</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" @selected((string) old('role_id', $item->role_id) === (string) $role->id)>{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label for="email" class="form-label">Email de connexion</label>
                <input id="email" type="email" name="email" class="form-control" value="{{ old('email', $item->email) }}" required>
            </div>
            <div class="col-md-3">
                <label for="password" class="form-label">{{ $isEdit ? 'Nouveau mot de passe' : 'Mot de passe' }}</label>
                <input id="password" type="password" name="password" class="form-control" {{ $isEdit ? '' : 'required' }}>
                @if($isEdit)
                    <small class="text-body-secondary">Laisser vide pour conserver le mot de passe actuel.</small>
                @endif
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <div class="form-check form-switch">
                    <input type="hidden" name="active" value="0">
                    <input class="form-check-input" type="checkbox" role="switch" id="active" name="active" value="1" @checked(old('active', $item->active ?? 1))>
                    <label class="form-check-label" for="active">Compte actif</label>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mt-3">
    <div class="card-body">
        <h6 class="mb-3">Affectation</h6>
        <div class="row g-3 mb-2">
            <div class="col-12">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="organisation_type" id="org_agence" value="agence" @checked($organisationType === 'agence')>
                    <label class="form-check-label" for="org_agence">Agence</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="organisation_type" id="org_entite" value="entite" @checked($organisationType === 'entite')>
                    <label class="form-check-label" for="org_entite">Entite</label>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-md-6" data-org-section="agence">
                <label for="agence_id" class="form-label">Agence</label>
                <select id="agence_id" name="agence_id" class="form-control">
                    <option value="0">Aucune</option>
                    @foreach($agences as $agence)
                        <option value="{{ $agence->id }}" @selected((string) old('agence_id', $item->agence_id) === (string) $agence->id)>{{ $agence->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6" data-org-section="agence">
                <label class="form-label">Direction</label>
                <input type="text" class="form-control" value="{{ $directionLabel }}" disabled>
            </div>

            <div class="col-md-6" data-org-section="entite">
                <label for="organisation_entite_id" class="form-label">Entite</label>
                <select id="organisation_entite_id" name="organisation_entite_id" class="form-control">
                    <option value="">Selectionner une entite</option>
                    @foreach($organisationEntites as $entite)
                        <option value="{{ $entite->id }}" @selected((string) old('organisation_entite_id', $item->organisation_entite_id) === (string) $entite->id)>
                            {{ $entite->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        function applyOrgVisibility() {
            const type = document.querySelector('input[name="organisation_type"]:checked')?.value || 'agence';
            document.querySelectorAll('[data-org-section="agence"]').forEach(el => el.style.display = type === 'agence' ? '' : 'none');
            document.querySelectorAll('[data-org-section="entite"]').forEach(el => el.style.display = type === 'entite' ? '' : 'none');
            if (type === 'agence') {
                const ent = document.getElementById('organisation_entite_id');
                if (ent) ent.value = '';
            } else {
                const ag = document.getElementById('agence_id');
                if (ag) ag.value = '0';
            }
        }
        document.querySelectorAll('input[name="organisation_type"]').forEach(r => r.addEventListener('change', applyOrgVisibility));
        applyOrgVisibility();
    })();
</script>

<div class="d-flex justify-content-between align-items-center mt-4">
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Retour a la liste</a>
    <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Enregistrer les modifications' : 'Creer le compte' }}</button>
</div>
