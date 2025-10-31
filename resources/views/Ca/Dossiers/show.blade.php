@extends('Layouts.ca')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">ANGARA</a></li>
       <li class="breadcrumb-item"><a href="#">INSTRUCTION</a></li>
       <li class="breadcrumb-item active" aria-current="page">{{ $item['name'] }}</li>
    </ol>
 </nav>
@endsection

@section('actions')
<div class="btn-group">
    <button type="button" class="btn btn-xs btn-outline-primary dropdown-toggle hstack gap-2" data-bs-toggle="dropdown" aria-expanded="false">
    Actions
    <span class="vr"></span>
    </button>
    <ul class="dropdown-menu analyse">
        <li><a data-sequence="7" class="dropdown-item" data-bs-target="#report1Modal" data-bs-toggle="modal" href="#">Conclusions motivées, recommandations du gestionnaire</a></li>
    </ul>
</div>
@endsection




@section('content')
<div>
    <div style="" class="row">
        <div class="col-md-3 col-sm-12">
            <div style="max-height: 600px; overflow: scroll;" class="card">
                <div class="card-header">
                    <p class="lh-base"><span class="label">ENTREPRISE : </span> <span>{{ $item->entreprise?->name }}</span></p>
                    <p class="lh-base"><span class="label">PROGRAMME : </span> <span>{{ $item->programme?->name }}</span></p>
                </div>
                <div class="card-footer">
                    @if($sme)
                        <h3>{{ $sme['name'] }}</h3>
                        <h4>{{ $sme['mention'] }}</h4>
                        <p class="lh-base">{{ $sme['description'] }}</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-9 col-sm-12">
            <div style="max-height: 600px; overflow: scroll;" class="card">
                <div style="" class="card-body">
                    <div class="">
                        <!-- Underline nav tabs with base -->
                        <div class="tab-base">
                        <!-- Nav tabs -->
                        <ul style="position: sticky; top:0;" class="nav nav-underline nav-component border-bottom" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link px-3 active" data-bs-toggle="tab" data-bs-target="#_dm-coTabsBaseHome" type="button" role="tab" aria-controls="home" aria-selected="true">GRILLE DE NOTATION</button>
                            </li>
                        </ul>


                        <!-- Tabs content -->
                        <div class="tab-content">
                            <div id="_dm-coTabsBaseHome" class="tab-pane fade active show" role="tabpanel" aria-labelledby="home-tab">

                                <table class="table table-sm table-bordered table-notation">
                                    <thead>
                                        <tr>
                                            <th>Critère Principaux</th>
                                            <td>&numero; sous-critère</td>
                                            <td>Pourcentage</td>
                                            <th>Sous - critère</th>
                                            <th>Valeur</th>
                                            <th>Note par sous-critere</th>
                                            <th>Note pondérée</th>

                                        </tr>
                                    </thead>
                                    @if(count($indicateurs))
                                    <tbody>
                                        <tr>
                                            <th class="vertical-align" rowspan="{{ count($criteres[0]['souscriteres'])+1 }}">{{ $criteres[0]['name'] }}</th>
                                        </tr>
                                        @foreach($criteres[0]['souscriteres'] as $sc)
                                            <tr class="border">
                                                <td class="border">{{ $sc['sequence'] }}</td>
                                                <td class="border">{{ $sc['default'] }}%</td>
                                                <td>{{ $sc['name'] }}</td>

                                                <td>{{ isset($sc['reponse']['choice'])?$sc['reponse']['choice']['valeur']:'-' }} <span class="float-right"><button data-bs-toggle="modal" data-bs-target="#critereModal" data-name="{{ $sc['name'] }}" data-dossier_id="{{ $item->id }}" data-programme_id="{{ $item->programme_id }}" data-id="{{ $sc['id'] }}" class="btn btn-xs btn-critere"><i class="pli-pencil"></i></button></span></td>
                                                <td>{{ isset($sc['reponse']['choice'])?$sc['reponse']['note']:'-' }}</td>
                                                <td>{{ isset($sc['reponse']['choice'])?($sc['reponse']['note']*$sc['default']/100):'-' }}</td>

                                            </tr>
                                        @endforeach
                                        <tr class="bg-light">
                                            <td colspan="4"></td>
                                            <th colspan="2">Note critère pondérée</th>
                                            <th>{{ isset($criteres[0]['note'])?$criteres[0]['note']:0 }}</th>
                                        </tr>
                                        <tr>
                                            <th class="vertical-align" rowspan="{{ count($criteres[1]['souscriteres'])+1 }}">{{ $criteres[1]['name'] }}</th>
                                        </tr>
                                        @foreach($criteres[1]['souscriteres'] as $sc)
                                            <tr>
                                                <td>{{ $sc['sequence'] }}</td>
                                                <td>{{ $sc['default'] }}%</td>
                                                <td>{{ $sc['name'] }}</td>
                                                <td>{{ isset($sc['reponse']['choice'])?$sc['reponse']['choice']['valeur']:'-' }} <span class="float-right"><button data-bs-toggle="modal" data-bs-target="#critereModal" data-name="{{ $sc['name'] }}" data-dossier_id="{{ $item->id }}" data-programme_id="{{ $item->programme_id }}" data-id="{{ $sc['id'] }}" class="btn btn-xs btn-critere"><i class="pli-pencil"></i></button></span></td>
                                                <td>{{ isset($sc['reponse']['choice'])?$sc['reponse']['note']:'-' }}</td>
                                                <td>{{ isset($sc['reponse']['choice'])?($sc['reponse']['note']*$sc['default']/100):'-' }}</td>

                                            </tr>
                                        @endforeach
                                        <tr class="bg-light">
                                            <td colspan="4"></td>
                                            <th colspan="2">Note critère pondérée</th>
                                            <th>{{ isset($criteres[1]['note'])?$criteres[1]['note']:0  }}</th>
                                        </tr>
                                        <tr>
                                            <th class="vertical-align" rowspan="{{ count($indicateurs[0]['notation']['details'])+1 }}">FINANCE</th>
                                        </tr>
                                            @foreach($indicateurs[0]['notation']['details'] as $sc)
                                            <tr>
                                                <td>{{ $sc['sequence'] }}</td>
                                                <td>{{ $sc['pourcentage'] }}%</td>
                                                <td>{{ $sc['critere'] }}</td>
                                                <td>{{ $sc['valeur'] }}</td>
                                                <td>{{ $sc['note'] }}</td>
                                                <td>{{ $sc['pondere'] }}</td>

                                            </tr>
                                            @endforeach
                                            <tr class="bg-light">
                                                <td colspan="4"></td>
                                                <th colspan="2">Note critère pondérée</th>
                                                <th >{{ $indicateurs[0]['notation']['note'] }}</th>
                                            </tr>
                                            <tr>
                                                <th class="vertical-align" rowspan="{{ count($criteres[3]['souscriteres'])+1 }}">{{ $criteres[3]['name'] }}</th>
                                            </tr>
                                            @foreach($criteres[3]['souscriteres'] as $sc)
                                                <tr>
                                                    <td>{{ $sc['sequence'] }}</td>
                                                    <td>{{ $sc['default'] }}%</td>
                                                    <td>{{ $sc['name'] }}</td>
                                                    <td>{{ isset($sc['reponse']['choice'])?$sc['reponse']['choice']['valeur']:'-' }} <span class="float-right"><button data-bs-toggle="modal" data-bs-target="#critereModal" data-name="{{ $sc['name'] }}" data-dossier_id="{{ $item->id }}" data-programme_id="{{ $item->programme_id }}" data-id="{{ $sc['id'] }}" class="btn btn-xs btn-critere"><i class="pli-pencil"></i></button></span></td>
                                                    <td>{{ isset($sc['reponse']['choice'])?$sc['reponse']['note']:'-' }}</td>
                                                    <td>{{ isset($sc['reponse']['choice'])?($sc['reponse']['note']*$sc['default']/100):'-' }}</td>

                                                </tr>
                                            @endforeach
                                            <tr class="bg-light">
                                                <td colspan="4"></td>
                                                <th colspan="2">Note critère pondérée</th>
                                                <th>{{ isset($criteres[3]['note'])?$criteres[3]['note']:0  }}</th>
                                            </tr>
                                            <tr class="border border-dark">
                                                <th colspan="3"></th>
                                                <th colspan="2">NOTE PONDERE FINALE/CRITERE</th>
                                                <th colspan="2">NOTATION PME</th>
                                            </tr>
                                            <tr style="position: sticky; top: 30px" class="border border-dark">
                                                <th colspan="4"></th>
                                                <th class="fw-bold" colspan="2">{{ $item['note'] }}</th>
                                                <th class="fw-bold" colspan="2">{{ $sme['name'] }}</th>
                                            </tr>
                                    </tbody>
                                    @endif
                                </table>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


    <script>
        const labels = [
            "Brèves données générales actualisées sur l'emprunteur / Diagnostic des options stratégiques et politiques (Vision du ou des promoteur(s), aspect juridique, répartition du capital, organigramme -à joindre éventuellement en annexe-, gestionnaireistrateurs, équipe de direction, aspects économiques et commerciaux macro et méso, relations avec d'autres Institutions financières ou Directions de l’établissement, principaux banquiers, auditeurs).",
            "Analyse critique d'ensemble / Diagnostic opérationnel de l'emprunteur, aspects non financiers (ses activités, son processus de production, ses relations d’affaires, le climat social de l’entreprise, perspectives de développement à moyen terme, etc.)",
            "Analyse financière de l'emprunteur (passé récent, présent, futur) / Aspects financiers du Diagnostic opérationnel (analyse du compte de résultats) ; Diagnostic financier (analyse du bilan) ; Diagnostic prévisionnel (analyse des états financiers prévisionnels)",
            `Appuis financiers et non-financiers proposés - (* quand applicable) :
            - Type, objet (description détaillée), montant, syndication (*), validité des concours.
            - Formes d'utilisation, autres clients autorisés dans le groupe ou la filière (*), sous-limites (*).
            - Modalités de remboursement, amortissement ou de suivi-évaluation (*).
            - Garanties, protections (telles que sûretés réelles, personnelles, cautionnement mutuel, garantie souveraine, lettres d'intention, covenants, ...), liens entre les sûretés et les formes d'utilisation. `,
            `Analyse du risque et de la capacité de remboursement de l'emprunteur (risque global, spécifique, technique, juridique, de marché), sorties des crédits.`,
            `Rentabilité de la relation pour l’établissement (conditions, commissions, retombées attendues, soldes moyens, commentaires ratios de couverture de risques).`,
            `Conclusions motivées, recommandations de l’Analyste Financier`,
        ]
        $('.analyse .dropdown-item').click(function(){
            var seq = $(this).data('sequence')
            console.log(seq)
            description = labels[seq-1]
            $('#description').text(description)
            $('#sequence').val(seq)
        })

        $('.btn-edit').click(function(){
            var id = $(this).data('engagement_id')
            $('#engagement_id').val(id)
        })
    </script>

    <script>
            $('.btn-critere').click(function(){
            var url = "{{ route('gestionnaire.instruction.critere.choices') }}"
            var name = $(this).data('name')
            var dossier_id = $(this).data('dossier_id')
            var id = $(this).data('id')
            $('#name').text(name)
            $('#_dossier_id').val(dossier_id)
            $('#id').val(id)

            $.ajax({
                url: url,
                type: 'get',
                dataType:'json',
                data: {id:id},
                success:function(data){
                    console.log(data)
                    $('#critere_id').html('')
                    $('#critere_id').append(`<option value="">Choisir ...</option>`)
                    data.forEach(choice => {
                        $('#critere_id').append(`<option value=${choice.id}>${choice.valeur}</option>`)
                    });
                    //window.location.replace('/gestionnaire/instruction/dossier/'+dossier_id)
                },
                //processData: false,
               // contentType: false
            } );
        })
        //var url = "{{ route('gestionnaire.instruction.dsf') }}"

    </script>

@endsection
