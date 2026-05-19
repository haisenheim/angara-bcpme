@php
    $readOnly = $readOnly ?? false;
    $noteFinale = $noteFinale ?? ($item->note ?? null);
    if (($sme ?? null) === null && $noteFinale !== null && (int) $noteFinale >= 1) {
        $sme = \App\Helpers\DossierHelper::resolveSmeFromNoteFinale((int) $noteFinale);
    }
    if (! isset($smeMention) && ! isset($smeDescription) && ($sme ?? null)) {
        $smeParts = \App\Helpers\DossierHelper::smeMentionEtDescription($sme);
        $smeMention = $smeParts['mention'];
        $smeDescription = $smeParts['description'];
    }
    $indicateurReference = $indicateurReference ?? ($indicateurs[0] ?? null);
    $notationFinance = $indicateurReference?->notation ?? ($indicateurReference['notation'] ?? null);
@endphp
<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent border-0 py-3 d-flex align-items-center justify-content-between">
        <h6 class="mb-0 fw-semibold"><i class="demo-psi-bar-chart me-2 text-primary"></i>Grille de notation</h6>
    </div>
    <div class="card-body overflow-auto" style="max-height: 100vh;">
        @if(count($indicateurs ?? []))
            <div class="table-responsive">
                <table class="table table-sm table-hover table-notation align-middle">
                    <thead class="table-light sticky-top">
                        <tr>
                            <th>Critère principal</th>
                            <th>N°</th>
                            <th>%</th>
                            <th>Sous-critère</th>
                            <th>Valeur</th>
                            <th>Note</th>
                            <th>Pondérée</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($criteres[0]))
                            <tr>
                                <th class="vertical-align bg-white" rowspan="{{ count($criteres[0]['souscriteres'] ?? []) + 1 }}">{{ $criteres[0]['name'] }}</th>
                            </tr>
                            @foreach($criteres[0]['souscriteres'] ?? [] as $sc)
                                <tr>
                                    <td>{{ $sc['sequence'] ?? '-' }}</td>
                                    <td>{{ $sc['default'] ?? 0 }}%</td>
                                    <td>{{ $sc['name'] ?? '-' }}</td>
                                    <td>
                                        {{ isset($sc['reponse']['choice']) ? $sc['reponse']['choice']['valeur'] : '-' }}
                                        @unless($readOnly)
                                            <button type="button" data-bs-toggle="modal" data-bs-target="#critereModal" data-name="{{ $sc['name'] ?? '' }}" data-dossier_id="{{ $item->id }}" data-programme_id="{{ $item->programme_id }}" data-id="{{ $sc['id'] ?? '' }}" class="btn btn-sm btn-link p-0 ms-1 btn-critere" title="Modifier"><i class="demo-psi-pen-5"></i></button>
                                        @endunless
                                    </td>
                                    <td>{{ isset($sc['reponse']['choice']) ? $sc['reponse']['note'] : '-' }}</td>
                                    <td>{{ isset($sc['reponse']['choice']) ? round($sc['reponse']['note'] * ($sc['default'] ?? 0) / 100, 2) : '-' }}</td>
                                </tr>
                            @endforeach
                            <tr class="table-light">
                                <td colspan="4"></td>
                                <th colspan="2">Note critère pondérée</th>
                                <th>{{ $criteres[0]['note'] ?? 0 }}</th>
                            </tr>
                        @endif

                        @if(isset($criteres[1]))
                            <tr>
                                <th class="vertical-align bg-white" rowspan="{{ count($criteres[1]['souscriteres'] ?? []) + 1 }}">{{ $criteres[1]['name'] }}</th>
                            </tr>
                            @foreach($criteres[1]['souscriteres'] ?? [] as $sc)
                                <tr>
                                    <td>{{ $sc['sequence'] ?? '-' }}</td>
                                    <td>{{ $sc['default'] ?? 0 }}%</td>
                                    <td>{{ $sc['name'] ?? '-' }}</td>
                                    <td>
                                        {{ isset($sc['reponse']['choice']) ? $sc['reponse']['choice']['valeur'] : '-' }}
                                        @unless($readOnly)
                                            <button type="button" data-bs-toggle="modal" data-bs-target="#critereModal" data-name="{{ $sc['name'] ?? '' }}" data-dossier_id="{{ $item->id }}" data-programme_id="{{ $item->programme_id }}" data-id="{{ $sc['id'] ?? '' }}" class="btn btn-sm btn-link p-0 ms-1 btn-critere" title="Modifier"><i class="demo-psi-pen-5"></i></button>
                                        @endunless
                                    </td>
                                    <td>{{ isset($sc['reponse']['choice']) ? $sc['reponse']['note'] : '-' }}</td>
                                    <td>{{ isset($sc['reponse']['choice']) ? round($sc['reponse']['note'] * ($sc['default'] ?? 0) / 100, 2) : '-' }}</td>
                                </tr>
                            @endforeach
                            <tr class="table-light">
                                <td colspan="4"></td>
                                <th colspan="2">Note critère pondérée</th>
                                <th>{{ $criteres[1]['note'] ?? 0 }}</th>
                            </tr>
                        @endif

                        @if(!empty($notationFinance['details']))
                            <tr>
                                <th class="vertical-align bg-white" rowspan="{{ count($notationFinance['details']) + 1 }}">Finance</th>
                            </tr>
                            @foreach($notationFinance['details'] as $sc)
                                <tr>
                                    <td>{{ $sc['sequence'] ?? '-' }}</td>
                                    <td>{{ $sc['pourcentage'] ?? 0 }}%</td>
                                    <td>{{ $sc['critere'] ?? '-' }}</td>
                                    <td>{{ $sc['valeur'] ?? '-' }}</td>
                                    <td>{{ $sc['note'] ?? '-' }}</td>
                                    <td>{{ $sc['pondere'] ?? '-' }}</td>
                                </tr>
                            @endforeach
                            <tr class="table-light">
                                <td colspan="4"></td>
                                <th colspan="2">Note critère pondérée</th>
                                <th>{{ $notationFinance['note'] ?? 0 }}</th>
                            </tr>
                        @endif

                        @if(isset($criteres[3]))
                            <tr>
                                <th class="vertical-align bg-white" rowspan="{{ count($criteres[3]['souscriteres'] ?? []) + 1 }}">{{ $criteres[3]['name'] }}</th>
                            </tr>
                            @foreach($criteres[3]['souscriteres'] ?? [] as $sc)
                                <tr>
                                    <td>{{ $sc['sequence'] ?? '-' }}</td>
                                    <td>{{ $sc['default'] ?? 0 }}%</td>
                                    <td>{{ $sc['name'] ?? '-' }}</td>
                                    <td>
                                        {{ isset($sc['reponse']['choice']) ? $sc['reponse']['choice']['valeur'] : '-' }}
                                        @unless($readOnly)
                                            <button type="button" data-bs-toggle="modal" data-bs-target="#critereModal" data-name="{{ $sc['name'] ?? '' }}" data-dossier_id="{{ $item->id }}" data-programme_id="{{ $item->programme_id }}" data-id="{{ $sc['id'] ?? '' }}" class="btn btn-sm btn-link p-0 ms-1 btn-critere" title="Modifier"><i class="demo-psi-pen-5"></i></button>
                                        @endunless
                                    </td>
                                    <td>{{ isset($sc['reponse']['choice']) ? $sc['reponse']['note'] : '-' }}</td>
                                    <td>{{ isset($sc['reponse']['choice']) ? round($sc['reponse']['note'] * ($sc['default'] ?? 0) / 100, 2) : '-' }}</td>
                                </tr>
                            @endforeach
                            <tr class="table-light">
                                <td colspan="4"></td>
                                <th colspan="2">Note critère pondérée</th>
                                <th>{{ $criteres[3]['note'] ?? 0 }}</th>
                            </tr>
                        @endif

                        <tr class="table-dark">
                            <th colspan="4"></th>
                            <th colspan="2">Note pondérée finale</th>
                            <th colspan="2">Notation PME</th>
                        </tr>
                        <tr class="table-dark">
                            <th colspan="4"></th>
                            <th class="fw-bold" colspan="2">{{ $noteFinale ?? '—' }}</th>
                            <th class="fw-bold" colspan="2">{{ $sme->name ?? $sme['name'] ?? '—' }}</th>
                        </tr>
                        @if($sme && (!empty($smeMention ?? null) || !empty($sme->mention ?? $sme['mention'] ?? null)))
                            <tr class="table-dark">
                                <th colspan="4" class="align-top">Mention</th>
                                <td colspan="4">
                                    @include('RoleSpace.dossiers.partials._sme_mention_badge', [
                                        'mention' => $smeMention ?? $sme->mention ?? $sme['mention'] ?? null,
                                    ])
                                </td>
                            </tr>
                        @endif
                        @if($sme && (!empty($smeDescription ?? null) || !empty($sme->description ?? $sme['description'] ?? null)))
                            <tr class="table-dark">
                                <th colspan="4" class="align-top">Avis SME</th>
                                <td colspan="4" class="small">{{ $smeDescription ?? $sme->description ?? $sme['description'] }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="demo-psi-file-search text-muted" style="font-size: 3rem;"></i>
                <p class="text-muted mt-3 mb-2">Aucune donnée DSF importée</p>
                <p class="small text-muted mb-0">
                    @if($readOnly)
                        L’analyste n’a pas encore importé de fichier DSF pour ce dossier, ou les indicateurs ne sont pas disponibles.
                    @else
                        Importez un fichier DSF dans le formulaire à gauche pour afficher la grille de notation.
                    @endif
                </p>
            </div>
        @endif
    </div>
</div>
