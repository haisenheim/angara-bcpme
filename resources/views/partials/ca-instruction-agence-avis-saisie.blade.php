{{-- Avis du chef d’agence (Summernote) puis transmission au REXP ; après transmission : lecture seule. --}}
@php
    /** @var \App\Models\Dossier $dossier */
    $transmitted = $dossier->isInstructionCaTransmittedToExploitation();
@endphp
@if($dossier->isInstructionValidatedByAgence())
    <div class="row justify-content-center mt-4 pt-2 mb-4 border-top">
        <div class="col-12 col-xl-11">
            <div class="card border-0 shadow-sm border-start border-3 border-success">
                <div class="card-header bg-transparent border-0 py-3">
                    <h6 class="mb-0 fw-semibold"><i class="demo-psi-pen-5 me-2 text-primary"></i>Avis du chef d’agence</h6>
                    <p class="text-muted small mb-0 mt-1">
                        @if($transmitted)
                            Lecture seule — le dossier a été transmis au responsable exploitation ; l’avis ne peut plus être modifié.
                        @else
                            Après validation de la transmission par l’agence. Enregistrez un avis, puis transmettez le dossier au responsable exploitation.
                        @endif
                    </p>
                </div>
                <div class="card-body pt-0">
                    @if($dossier->instruction_agence_ca_avis_saved_at)
                        <p class="small text-body-secondary mb-2">
                            <strong>Dernier enregistrement de l’avis :</strong>
                            {{ $dossier->instruction_agence_ca_avis_saved_at->format('d/m/Y \à H:i') }}
                            @if($dossier->instructionAgenceCaAvisSavedBy)
                                <span class="text-nowrap">— {{ $dossier->instructionAgenceCaAvisSavedBy->name }}</span>
                            @endif
                        </p>
                    @endif

                    @if($transmitted && $dossier->instruction_ca_transmitted_to_exploitation_at)
                        <p class="small text-success mb-3">
                            <strong>Transmis au responsable exploitation</strong> — le {{ $dossier->instruction_ca_transmitted_to_exploitation_at->format('d/m/Y \à H:i') }}
                            @if($dossier->instructionCaTransmittedToExploitationBy)
                                <span class="text-nowrap">— {{ $dossier->instructionCaTransmittedToExploitationBy->name }}</span>
                            @endif
                        </p>
                    @endif

                    @if($transmitted)
                        <div class="summernote-wrapper summernote-wrapper--compact mb-0">
                            <label class="form-label text-muted small">Avis enregistré</label>
                            <div class="border rounded p-3 bg-light small rich-text-rendered">{!! $dossier->instruction_agence_ca_avis !!}</div>
                        </div>
                    @else
                        <form id="form-ca-instruction-agence-avis" method="post" action="{{ route('ca.dossier.instruction-agence-avis.save', $dossier->token) }}">
                            @csrf
                            <div class="summernote-wrapper summernote-wrapper--compact mb-3">
                                <label class="form-label" for="instruction_agence_ca_avis">Votre avis</label>
                                <textarea name="instruction_agence_ca_avis" id="instruction_agence_ca_avis" class="form-control js-summernote-ca-instruction-avis" rows="8">{!! old('instruction_agence_ca_avis', $dossier->instruction_agence_ca_avis) !!}</textarea>
                            </div>
                            @error('instruction_agence_ca_avis')
                                <div class="text-danger small mb-2">{{ $message }}</div>
                            @enderror
                            <button type="submit" class="btn btn-primary">Enregistrer l’avis</button>
                        </form>

                        @if($dossier->hasInstructionAgenceCaAvisSubstance())
                            <hr class="my-4">
                            <h6 class="small fw-semibold text-uppercase text-muted mb-2">Transmission</h6>
                            <p class="small text-muted mb-3">Une fois l’avis enregistré, vous pouvez transmettre le dossier au responsable exploitation. Cette action est définitive : vous ne pourrez plus modifier l’avis.</p>
                            <form method="post" action="{{ route('ca.dossier.transmit-exploitation', $dossier->token) }}" onsubmit="return confirm('Transmettre ce dossier au responsable exploitation ? L’avis du chef d’agence ne pourra plus être modifié.');">
                                @csrf
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="demo-psi-arrow-right me-1"></i> Transmettre au responsable exploitation
                                </button>
                            </form>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif
