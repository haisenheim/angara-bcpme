@php
    /** @var \App\Models\Dossier $dossier */
@endphp

@error('submission')
    <div class="alert alert-danger">{{ $message }}</div>
@enderror

@include('RoleSpace.dossiers.partials._analyste_reject_banner', [
    'rejected' => $dossier->isExploitationAnalysteRejectedByRexp(),
    'motif' => $dossier->exploitation_analyste_reject_motif,
    'rejectedAt' => $dossier->exploitation_analyste_rejected_at,
    'rejectedBy' => $dossier->exploitationAnalysteRejectedBy,
    'libelleAction' => 'modifier vos rubriques d’analyse et retransmettre au responsable exploitation',
])

<div class="row g-3">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 py-3">
                <h6 class="mb-0 fw-semibold"><i class="demo-psi-upload me-2 text-primary"></i>Import DSF</h6>
            </div>
            <div class="card-body">
                <form enctype="multipart/form-data" action="{{ route('analyste.dossier.dsf') }}" method="post">
                    @csrf
                    <input type="hidden" name="dossier_id" value="{{ $dossier->id }}">
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

        @if($sme ?? null)
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

    <div class="col-lg-8">
        @include('RoleSpace.dossiers.partials.instruction_grille_notation', [
            'item' => $dossier,
            'criteres' => $criteres ?? [],
            'indicateurs' => $indicateurs ?? [],
            'sme' => $sme ?? null,
            'readOnly' => false,
        ])
    </div>
</div>

@if($dossier->analyste_id)
    <div class="card border-0 shadow-sm mb-4 mt-3">
        <div class="card-header bg-transparent border-0 py-3">
            <h6 class="mb-0 fw-semibold"><i class="demo-psi-file-edit me-2 text-primary"></i>Saisie analyste financier</h6>
            <p class="small text-muted mb-0 mt-1">Sept rubriques à renseigner (Summernote), en complément de la grille de notation et de l’import DSF. Utilisez le menu <strong>Actions</strong> pour accéder rapidement à chaque zone. <strong>Enregistrer le brouillon</strong> permet de reprendre plus tard. La soumission au responsable exploitation exige les <strong>sept</strong> rubriques complètes.</p>
        </div>
        <div class="card-body pt-0">
            @if($dossier->isInstructionSubmittedToExploitation())
                <p class="small text-success mb-2">
                    <strong>Transmission au responsable exploitation (analyste financier)</strong> — le {{ $dossier->exploitation_analyste_transmitted_to_exploitation_at->format('d/m/Y à H:i') }}
                    @if($dossier->exploitationAnalysteTransmittedToExploitationBy)
                        — {{ $dossier->exploitationAnalysteTransmittedToExploitationBy->name }}
                    @endif
                </p>
                <p class="small text-muted mb-3">Les rubriques ci-dessous ne sont plus modifiables après soumission.</p>
                @foreach(\App\Models\Dossier::EXPLOITATION_AF_INSTRUCTION_SECTIONS as $column => $label)
                    <div class="mb-4 pb-3 border-bottom border-light-subtle">
                        <h6 class="form-label fw-semibold mb-2 text-body-secondary">{{ $loop->iteration }}. {{ $label }}</h6>
                        @if($dossier->afInstructionSectionHasSubstance($column))
                            <div class="rich-text-rendered small border rounded p-3 bg-light">{!! $dossier->{$column} !!}</div>
                        @else
                            <p class="text-muted small mb-0">—</p>
                        @endif
                    </div>
                @endforeach
            @else
                <form id="form-analyste-af-sections" method="post" class="mb-0">
                    @csrf
                    @foreach(\App\Models\Dossier::EXPLOITATION_AF_INSTRUCTION_SECTIONS as $column => $label)
                        <div id="af-saisie-{{ $loop->iteration }}" class="mb-4 pb-2 border-bottom border-light-subtle">
                            <label class="form-label fw-semibold" for="af_editor_{{ $column }}">{{ $loop->iteration }}. {{ $label }}</label>
                            <div class="summernote-wrapper">
                                <textarea
                                    name="{{ $column }}"
                                    id="af_editor_{{ $column }}"
                                    class="form-control js-af-instruction-summernote"
                                    rows="6"
                                    data-placeholder="{{ e($label) }}…"
                                >{!! old($column, $dossier->{$column} ?? '') !!}</textarea>
                            </div>
                        </div>
                    @endforeach
                    @if($dossier->exploitation_analyste_instruction_avis_saved_at)
                        <p class="small text-muted mb-3 mb-md-2">Dernière sauvegarde du brouillon : le {{ $dossier->exploitation_analyste_instruction_avis_saved_at->format('d/m/Y à H:i') }}.</p>
                    @endif
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        <button type="submit" formaction="{{ route('analyste.dossiers.instruction-avis-brouillon', $dossier) }}" class="btn btn-outline-primary">
                            Enregistrer le brouillon
                        </button>
                        <button type="submit" formaction="{{ route('analyste.dossiers.soumettre-exploitation', $dossier) }}" class="btn btn-success" id="btn-soumettre-rexp-analyste">
                            Soumettre au responsable exploitation pour validation
                        </button>
                    </div>
                    <p class="small text-muted mt-2 mb-0">Les sept rubriques doivent contenir du texte significatif avant soumission au responsable exploitation.</p>
                </form>
            @endif
        </div>
    </div>
@endif

<div class="mt-3">
    <h2 class="h5 fw-semibold mb-3">Simulations de crédit</h2>
    <x-simulator::scenarios-panel :dossier="$dossier" />
</div>
