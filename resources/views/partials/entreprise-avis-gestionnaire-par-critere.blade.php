@php
    /** @var \App\Models\Entreprise $item */
    $avisCollection = $item->critereAvis ?? collect();
    $avisMap = $avisCollection->keyBy('critere_id');
    $hasAnyAvis = $avisCollection->whereNotNull('avis')->isNotEmpty();
@endphp

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom py-3">
        <strong>Avis du gestionnaire par critère principal</strong>
        <p class="text-body-secondary small mb-0 mt-1">Synthèse rédigée par le gestionnaire après le questionnaire d’entrée en relation.</p>
    </div>
    <div class="card-body">
        @if(!isset($mr) || $mr->isEmpty())
            <p class="text-body-secondary mb-0">Questionnaire non renseigné.</p>
        @elseif(! $hasAnyAvis)
            <p class="text-body-secondary mb-0">Aucun avis du gestionnaire n’a été saisi pour l’instant.</p>
        @else
            <div class="accordion" id="accordionAvisGestionnaireCriteres">
                @foreach($mr as $result)
                    @php
                        $critereId = (int) ($result['critere']?->id ?? 0);
                        $avis = $critereId > 0 ? $avisMap->get($critereId) : null;
                    @endphp
                    @continue(! $avis || ! $avis->avis)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingAvisCritere{{ $critereId }}">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAvisCritere{{ $critereId }}" aria-expanded="false" aria-controls="collapseAvisCritere{{ $critereId }}">
                                {{ $result['critere']?->name ?? 'Critère' }}
                            </button>
                        </h2>
                        <div id="collapseAvisCritere{{ $critereId }}" class="accordion-collapse collapse" aria-labelledby="headingAvisCritere{{ $critereId }}" data-bs-parent="#accordionAvisGestionnaireCriteres">
                            <div class="accordion-body">
                                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
                                    <div class="small text-body-secondary">
                                        @if($avis->user)
                                            <strong>{{ $avis->user->name }}</strong>
                                        @else
                                            <strong>—</strong>
                                        @endif
                                    </div>
                                    <div class="small text-body-secondary">
                                        @if($avis->saved_at)
                                            Enregistré le {{ \Illuminate\Support\Carbon::parse($avis->saved_at)->format('d/m/Y \à H:i') }}
                                        @elseif($avis->updated_at)
                                            Mis à jour le {{ \Illuminate\Support\Carbon::parse($avis->updated_at)->format('d/m/Y \à H:i') }}
                                        @endif
                                    </div>
                                </div>
                                <div class="rich-text-rendered border rounded p-3 bg-light small">{!! $avis->avis !!}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

