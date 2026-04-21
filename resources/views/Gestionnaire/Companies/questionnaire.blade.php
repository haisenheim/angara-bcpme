@extends('Layouts.gestionnaire')

@php
    $allSousCriteres = $criteresPrincipaux->flatMap(fn ($c) => $c->questionnaireSousCriteres);
    $totalSousCriteres = $allSousCriteres->count();
    $totalQuestions = $allSousCriteres->sum(fn ($sc) => $sc->questions->count());
    $answeredQuestions = collect($reponses)->filter(fn ($choiceId) => (int) $choiceId !== 0)->count();
    $completionRate = $totalQuestions > 0 ? (int) round(($answeredQuestions / $totalQuestions) * 100) : 0;
    $backToLabel = $item->prospect ? 'Retour au prospect' : 'Retour au dossier';
    $listingRoute = $item->prospect ? route('gestionnaire.entreprises.prospects') : route('gestionnaire.entreprises.index');
    $listingLabel = $item->prospect ? 'Prospects' : 'Entreprises';
@endphp

@section('title', 'Mise en relation - ' . $item->name)
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('gestionnaire.dashboard') }}">Angara</a></li>
        <li class="breadcrumb-item"><a href="{{ $listingRoute }}">{{ $listingLabel }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('gestionnaire.entreprises.show', $item->token) }}">{{ Str::limit($item->name, 30) }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">Mise en relation</li>
    </ol>
</nav>
@endsection

@section('actions')
    <a href="{{ route('gestionnaire.entreprises.show', $item->token) }}" class="btn btn-outline-secondary btn-sm">
        <i class="demo-psi-arrow-left me-2"></i>{{ $backToLabel }}
    </a>
@endsection

@section('page-header')
    <div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <h5 class="page-title mb-0">Questionnaire de mise en relation</h5>
            @if($item->prospect)
                <span class="badge bg-danger">Prospect</span>
            @endif
        </div>
        <p class="text-body-secondary mb-0 mt-1">{{ $item->name }} — Répondez aux questions pour documenter l'entrée en relation.</p>
    </div>
@endsection

@push('styles')
<style>
.questionnaire-shell {
    --accent-color: #88b824;
    --accent-color-dark: #6f9a1d;
    --accent-soft: rgba(136, 184, 36, 0.10);
    --accent-soft-strong: rgba(136, 184, 36, 0.18);
}

.questionnaire-summary-card {
    border: 1px solid rgba(136, 184, 36, 0.16);
    background: linear-gradient(135deg, rgba(136, 184, 36, 0.12), rgba(255, 255, 255, 0.94));
}

.questionnaire-summary-metric {
    display: flex;
    flex-direction: column;
    gap: 0.15rem;
}

.questionnaire-summary-value {
    font-size: 1.4rem;
    font-weight: 700;
    color: #1f2937;
}

.questionnaire-progress {
    height: 0.55rem;
    border-radius: 999px;
    background: #e9eef5;
    overflow: hidden;
}

.questionnaire-progress-bar {
    height: 100%;
    border-radius: inherit;
    background: linear-gradient(90deg, var(--accent-color), var(--accent-color-dark));
    transition: width 0.2s ease;
}

.questionnaire-layout {
    border-radius: 1rem;
    overflow: hidden;
}

.questionnaire-sidebar {
    background: #f8fafc;
    border-right: 1px solid #e5e7eb;
}

.questionnaire-sidebar-header {
    padding: 1rem 1rem 0.5rem;
}

.questionnaire-nav {
    padding: 0 0.75rem 1rem;
}

.questionnaire-nav .nav-link {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
    width: 100%;
    margin-bottom: 0.5rem;
    padding: 0.8rem 0.9rem;
    border: 1px solid transparent;
    border-radius: 0.85rem;
    color: #334155;
    background: transparent;
    transition: all 0.2s ease;
}

.questionnaire-nav .nav-link:hover {
    border-color: #d9e2ec;
    background: #fff;
}

.questionnaire-nav .nav-link.active {
    color: #17310b;
    border-color: rgba(136, 184, 36, 0.28);
    background: rgba(136, 184, 36, 0.12);
    box-shadow: inset 0 0 0 1px rgba(136, 184, 36, 0.08);
}

.questionnaire-nav-label {
    display: flex;
    align-items: center;
    gap: 0.65rem;
    min-width: 0;
}

.questionnaire-nav-index {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 1.65rem;
    height: 1.65rem;
    border-radius: 999px;
    background: #e9eef5;
    color: #475569;
    font-size: 0.75rem;
    font-weight: 700;
    flex: 0 0 auto;
}

.questionnaire-nav .nav-link.active .questionnaire-nav-index {
    color: #fff;
    background: var(--accent-color);
}

.questionnaire-nav-title {
    min-width: 0;
    font-size: 0.9rem;
    font-weight: 600;
    line-height: 1.25;
    text-align: left;
}

.questionnaire-nav-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 3.1rem;
    padding: 0.2rem 0.55rem;
    border-radius: 999px;
    background: #fff;
    color: #64748b;
    font-size: 0.72rem;
    font-weight: 700;
}

.questionnaire-nav .nav-link.active .questionnaire-nav-count {
    color: #4e6d14;
    background: rgba(255, 255, 255, 0.72);
}

.questionnaire-pane {
    padding: 1.5rem;
}

.questionnaire-question-card {
    border: 1px solid #e5e7eb;
    border-radius: 1rem;
    padding: 1rem;
    background: #fff;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
}

.questionnaire-question-card + .questionnaire-question-card {
    margin-top: 1rem;
}

.questionnaire-question-label {
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 0.35rem;
}

.questionnaire-question-meta {
    font-size: 0.82rem;
    color: #64748b;
}

.questionnaire-choice {
    min-width: 230px;
    border-radius: 0.8rem;
    border-color: #d8dee6;
}

.questionnaire-choice:focus {
    border-color: var(--accent-color);
    box-shadow: 0 0 0 0.2rem rgba(136, 184, 36, 0.14);
}

.questionnaire-footer {
    position: sticky;
    bottom: 0;
    z-index: 2;
}

.questionnaire-save-btn {
    background: var(--accent-color);
    border-color: var(--accent-color);
}

.questionnaire-save-btn:hover,
.questionnaire-save-btn:focus {
    background: var(--accent-color-dark);
    border-color: var(--accent-color-dark);
}

@media (max-width: 991.98px) {
    .questionnaire-sidebar {
        border-right: 0;
        border-bottom: 1px solid #e5e7eb;
    }

    .questionnaire-pane {
        padding: 1rem;
    }
}

.questionnaire-by-critere {
    border-top: 1px solid #e5e7eb;
}

.questionnaire-critere-principal + .questionnaire-critere-principal {
    border-top: 1px solid #e5e7eb;
}

.questionnaire-cp-header {
    border-left: 4px solid var(--accent-color);
}

.questionnaire-cp-header--2 {
    border-left-color: #2563eb;
}

.questionnaire-cp-header--3 {
    border-left-color: #7c3aed;
}
</style>
@endpush

@section('content')
    <div class="questionnaire-shell">
        <div class="row g-3 justify-content-center mb-4">
            <div class="col-12 col-xl-11">
                <div class="card questionnaire-summary-card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="row g-3 align-items-center">
                            <div class="col-lg-6">
                                <p class="text-uppercase text-muted small fw-semibold mb-2">Progression du questionnaire</p>
                                <h5 class="mb-2">Complétez les réponses pour préparer la mise en relation.</h5>
                                <p class="text-body-secondary mb-0">Le questionnaire est structuré par <strong>critères principaux</strong>, puis par <strong>sous-critères</strong>. Vous pouvez enregistrer à tout moment.</p>
                            </div>
                            <div class="col-lg-6">
                                <div class="row g-3">
                                    <div class="col-6 col-md-3">
                                        <div class="questionnaire-summary-metric">
                                            <span class="text-muted small">Critères</span>
                                            <span class="questionnaire-summary-value">{{ $criteresPrincipaux->count() }}</span>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="questionnaire-summary-metric">
                                            <span class="text-muted small">Sous-critères</span>
                                            <span class="questionnaire-summary-value">{{ $totalSousCriteres }}</span>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="questionnaire-summary-metric">
                                            <span class="text-muted small">Questions</span>
                                            <span class="questionnaire-summary-value" id="questionnaire-total-count">{{ $totalQuestions }}</span>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="questionnaire-summary-metric">
                                            <span class="text-muted small">Répondues</span>
                                            <span class="questionnaire-summary-value" id="questionnaire-answered-count">{{ $answeredQuestions }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <div class="d-flex justify-content-between small text-muted mb-2">
                                        <span>Taux de complétion</span>
                                        <strong id="questionnaire-progress-label">{{ $completionRate }}%</strong>
                                    </div>
                                    <div class="questionnaire-progress">
                                        <div class="questionnaire-progress-bar" id="questionnaire-progress-bar" style="width: {{ $completionRate }}%;"></div>
                                    </div>
                                </div>
                                <div class="alert alert-success py-2 px-3 mt-3 mb-0 d-none" id="questionnaire-success-alert">
                                    Les réponses ont été enregistrées avec succès.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-12 col-xl-11">
                <form id="questionnaire-form" class="card border-0 shadow-sm questionnaire-layout">
                    @csrf
                    <input type="hidden" id="id" name="id" value="{{ $item->id }}">
                    <input type="hidden" id="token" name="token" value="{{ $item->token }}">

                    <div class="questionnaire-by-critere">
                        @forelse ($criteresPrincipaux as $criterePrincipal)
                            @php
                                $sections = $criterePrincipal->questionnaireSousCriteres;
                                $cpQuestionCount = $sections->sum(fn ($sc) => $sc->questions->count());
                                $cpHeaderMod = $loop->iteration % 3 === 2 ? 'questionnaire-cp-header--2' : ($loop->iteration % 3 === 0 ? 'questionnaire-cp-header--3' : '');
                            @endphp
                            <div class="questionnaire-critere-principal">
                                <div class="questionnaire-cp-header px-3 px-lg-4 py-3 bg-light {{ $cpHeaderMod }}">
                                    <p class="text-uppercase text-muted small fw-semibold mb-1">Critère principal {{ $loop->iteration }} / {{ $criteresPrincipaux->count() }}</p>
                                    <h5 class="mb-1">{{ $criterePrincipal->name }}</h5>
                                    <p class="text-body-secondary small mb-0">{{ $sections->count() }} sous-critère(s) · {{ $cpQuestionCount }} question(s)</p>
                                </div>

                                <div class="row g-0">
                                    <div class="col-lg-4 col-xl-3 questionnaire-sidebar">
                                        <div class="questionnaire-sidebar-header">
                                            <h6 class="text-muted text-uppercase small mb-1">Sous-critères</h6>
                                            <p class="text-body-secondary small mb-0">Sections de ce critère — naviguez entre les blocs.</p>
                                        </div>
                                        <div class="questionnaire-nav">
                                            <ul class="nav flex-column border-0" role="tablist">
                                                @foreach ($sections as $sc)
                                                    @php
                                                        $criterionTotal = $sc->questions->count();
                                                        $criterionAnswered = $sc->questions->filter(fn ($question) => isset($reponses[$question->id]) && (int) $reponses[$question->id] !== 0)->count();
                                                        $paneId = 'questionnaire-section-cp-'.$criterePrincipal->id.'-'.$sc->id;
                                                    @endphp
                                                    <li class="nav-item" role="presentation">
                                                        <button
                                                            class="nav-link {{ $loop->parent->first && $loop->first ? 'active' : '' }}"
                                                            data-bs-toggle="tab"
                                                            data-bs-target="#{{ $paneId }}"
                                                            type="button"
                                                            role="tab"
                                                            data-criterion-id="{{ $sc->id }}"
                                                        >
                                                            <span class="questionnaire-nav-label">
                                                                <span class="questionnaire-nav-index">{{ $loop->iteration }}</span>
                                                                <span class="questionnaire-nav-title">{{ $sc->name }}</span>
                                                            </span>
                                                            <span class="questionnaire-nav-count" id="criterion-count-{{ $sc->id }}">{{ $criterionAnswered }}/{{ $criterionTotal }}</span>
                                                        </button>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="col-lg-8 col-xl-9">
                                        <div class="tab-content questionnaire-pane">
                                            @foreach ($sections as $sc)
                                                @php
                                                    $criterionTotal = $sc->questions->count();
                                                    $criterionAnswered = $sc->questions->filter(fn ($question) => isset($reponses[$question->id]) && (int) $reponses[$question->id] !== 0)->count();
                                                    $paneId = 'questionnaire-section-cp-'.$criterePrincipal->id.'-'.$sc->id;
                                                @endphp
                                                <div id="{{ $paneId }}" class="tab-pane fade {{ $loop->parent->first && $loop->first ? 'show active' : '' }}" role="tabpanel">
                                                    <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap mb-4">
                                                        <div>
                                                            <p class="text-uppercase text-muted small fw-semibold mb-1">
                                                                Sous-critère {{ $loop->iteration }} / {{ $sections->count() }}
                                                            </p>
                                                            <h5 class="fw-semibold mb-1 text-success">{{ $sc->name }}</h5>
                                                            <p class="text-body-secondary mb-0 small">Critère : <strong>{{ $criterePrincipal->name }}</strong> — sélectionnez la réponse la plus adaptée pour chaque question.</p>
                                                        </div>
                                                        <span class="badge bg-light text-dark border px-3 py-2" id="criterion-pill-{{ $sc->id }}">{{ $criterionAnswered }} / {{ $criterionTotal }} répondues</span>
                                                    </div>

                                                    @forelse ($sc->questions as $question)
                                                        <div class="questionnaire-question-card">
                                                            <div class="row g-3 align-items-center">
                                                                <div class="col-lg-7">
                                                                    <label for="choice_{{ $question->id }}" class="questionnaire-question-label">{{ $question->name }}</label>
                                                                    <div class="questionnaire-question-meta">
                                                                        {{ $question->choices->count() }} option(s) disponible(s)
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-5">
                                                                    <select
                                                                        data-critere_id="{{ $sc->critere_id }}"
                                                                        data-sc_id="{{ $sc->id }}"
                                                                        data-question_id="{{ $question->id }}"
                                                                        data-criterion-group="{{ $sc->id }}"
                                                                        name="choice_{{ $question->id }}"
                                                                        id="choice_{{ $question->id }}"
                                                                        class="form-select choice questionnaire-choice"
                                                                    >
                                                                        <option value="0">Choisir une réponse...</option>
                                                                        @foreach ($question->choices as $choice)
                                                                            <option data-value="{{ $choice->value }}" value="{{ $choice->id }}" {{ ($reponses[$question->id] ?? 0) == $choice->id ? 'selected' : '' }}>
                                                                                {{ $choice->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @empty
                                                        <div class="alert alert-light border mb-0">Aucune question configurée pour cette section.</div>
                                                    @endforelse
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-4 text-center text-muted">
                                Aucun critère de questionnaire n’est configuré. Contactez l’administrateur.
                            </div>
                        @endforelse
                    </div>

                    <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center py-3 px-4 questionnaire-footer">
                        <a href="{{ route('gestionnaire.entreprises.show', $item->token) }}" class="btn btn-link text-muted px-0">{{ $backToLabel }}</a>
                        <button id="btn-save" type="submit" class="btn btn-primary questionnaire-save-btn">
                            <i class="demo-psi-check me-2"></i>Enregistrer les réponses
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const url = "{{ route('gestionnaire.entreprise.questionnaire.save') }}";
        const form = document.getElementById('questionnaire-form');
        const saveButton = document.getElementById('btn-save');
        const token = document.getElementById('token').value;
        const successAlert = document.getElementById('questionnaire-success-alert');
        const totalCountEl = document.getElementById('questionnaire-total-count');
        const answeredCountEl = document.getElementById('questionnaire-answered-count');
        const progressLabelEl = document.getElementById('questionnaire-progress-label');
        const progressBarEl = document.getElementById('questionnaire-progress-bar');
        const choices = Array.from(document.querySelectorAll('.choice'));

        function updateProgress() {
            const total = choices.length;
            const answered = choices.filter(function (select) {
                return parseInt(select.value, 10) !== 0;
            }).length;
            const percentage = total > 0 ? Math.round((answered / total) * 100) : 0;

            if (totalCountEl) totalCountEl.textContent = total;
            if (answeredCountEl) answeredCountEl.textContent = answered;
            if (progressLabelEl) progressLabelEl.textContent = percentage + '%';
            if (progressBarEl) progressBarEl.style.width = percentage + '%';

            const groupedCounts = {};
            choices.forEach(function (select) {
                const criterionId = select.dataset.criterionGroup;
                groupedCounts[criterionId] = groupedCounts[criterionId] || { total: 0, answered: 0 };
                groupedCounts[criterionId].total += 1;
                if (parseInt(select.value, 10) !== 0) {
                    groupedCounts[criterionId].answered += 1;
                }
            });

            Object.keys(groupedCounts).forEach(function (criterionId) {
                const count = groupedCounts[criterionId];
                const countBadge = document.getElementById('criterion-count-' + criterionId);
                const pill = document.getElementById('criterion-pill-' + criterionId);

                if (countBadge) {
                    countBadge.textContent = count.answered + '/' + count.total;
                }
                if (pill) {
                    pill.textContent = count.answered + ' / ' + count.total + ' répondues';
                }
            });
        }

        function collectAnswers() {
            const entrepriseId = document.getElementById('id').value;

            return choices.reduce(function (payload, select) {
                const option = select.options[select.selectedIndex];
                const choiceId = parseInt(select.value, 10) || 0;
                const value = option ? parseFloat(option.dataset.value) || 0 : 0;

                if (choiceId !== 0) {
                    payload.push({
                        critere_id: select.dataset.critere_id,
                        sous_critere_id: select.dataset.sc_id,
                        question_id: select.dataset.question_id,
                        choice_id: choiceId,
                        entreprise_id: entrepriseId,
                        value: value
                    });
                }

                return payload;
            }, []);
        }

        choices.forEach(function (select) {
            select.addEventListener('change', function () {
                if (successAlert) {
                    successAlert.classList.add('d-none');
                }
                updateProgress();
            });
        });

        updateProgress();

        form.addEventListener('submit', function (event) {
            event.preventDefault();

            const csrfToken = form.querySelector('input[name="_token"]').value;
            const payload = collectAnswers();
            const originalLabel = saveButton.innerHTML;

            saveButton.disabled = true;
            saveButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Enregistrement...';

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ choices: payload, _token: csrfToken })
            })
                .then(function (response) {
                    if (!response.ok) {
                        throw new Error('Erreur lors de l\'enregistrement');
                    }
                    return response.json();
                })
                .then(function () {
                    if (successAlert) {
                        successAlert.classList.remove('d-none');
                    }
                    setTimeout(function () {
                        window.location.replace('/gestionnaire/entreprises/' + token);
                    }, 500);
                })
                .catch(function (error) {
                    console.error(error);
                    alert('Une erreur est survenue lors de l\'enregistrement.');
                })
                .finally(function () {
                    saveButton.disabled = false;
                    saveButton.innerHTML = originalLabel;
                });
        });
    });
</script>
@endsection
