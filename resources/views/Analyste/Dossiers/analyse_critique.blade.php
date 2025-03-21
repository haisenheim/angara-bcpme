@extends('Layouts.analyste')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">ANGARA</a></li>
       <li class="breadcrumb-item"><a href="#">{{ $item->entreprise->name }}</a></li>
       <li class="breadcrumb-item active" aria-current="page">Grille d'analyse critique</li>
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
        <li><a data-sequence="1" class="dropdown-item" data-bs-target="#report1Modal" data-bs-toggle="modal" href="#">Brèves données générales actualisées sur l'emprunteur</a></li>
        <li><a data-sequence="2" class="dropdown-item" data-bs-target="#report1Modal" data-bs-toggle="modal" href="#">Analyse critique d'ensemble</a></li>
        <li><a data-sequence="3" class="dropdown-item" data-bs-target="#report1Modal" data-bs-toggle="modal" href="#">Analyse financière de l'emprunteur</a></li>
        <li><a data-sequence="4" class="dropdown-item" data-bs-target="#report1Modal" data-bs-toggle="modal" href="#">Appuis financiers et non-financiers proposés</a></li>
        <li><a data-sequence="5" class="dropdown-item" data-bs-target="#report1Modal" data-bs-toggle="modal" href="#">Analyse du risque et de la capacité de remboursement de l'emprunteur</a></li>
        <li><a data-sequence="6" class="dropdown-item" data-bs-target="#report1Modal" data-bs-toggle="modal" href="#">Rentabilité de la relation pour l’établissement</a></li>
        <li><a data-sequence="7" class="dropdown-item" data-bs-target="#report1Modal" data-bs-toggle="modal" href="#">Conclusions motivées, recommandations de l’Analyste Financier</a></li>
    </ul>
</div>
@endsection
@section('content')
    <div class="container">
        <div class="d-flex justify-content-center">
            <div style="width:800px" class="card">
                <div class="card-header p-4">
                    <h4 class="text-center mb-0">GRILLE D'ANALYSE CRITIQUE</h4>
                </div>
                <div class="card-body table-responsive">
                    <div id="" class="" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="mt-2 border rounded rounded-2 p-2">
                            <h4 class="fs-6">1. INFORMATIONS GENERALES</h4>
                            <p class="lh-base"><?= $item['donneesGenerales'] ?></p>
                        </div>
                        <div class="mt-2 border rounded rounded-2 p-2">
                            <h4 class="fs-6">2. ANALYSE D'ENSEMBLE</h4>
                            <p class="lh-base"><?= $item['analyseEnsemble'] ?></p>
                        </div>
                        <div class="mt-2 border rounded rounded-2 p-2">
                            <h4 class="fs-6">3. ANALYSE FINANCIERE</h4>
                            <p class="lh-base"><?= $item['analyseFinanciere'] ?></p>
                        </div>
                        <div class="mt-2 border rounded rounded-2 p-2">
                            <h4 class="fs-6">4. APPUIS FINANCIERS ET NON FINANCIERS</h4>
                            <p class="lh-base"><?= $item['appuis'] ?></p>
                        </div>
                        <div class="mt-2 border rounded rounded-2 p-2">
                            <h4 class="fs-6">5. ANALYSE DU RISQUE ET DE LA CAPACITE DE REMBOURSEMENT</h4>
                            <p class="lh-base"><?= $item['analyseRisque'] ?></p>
                        </div>
                        <div class="mt-2 border rounded rounded-2 p-2">
                            <h4 class="fs-6">6. RENTABILITE DE LA RELATION POUR L'ETABILISSEMENT</h4>
                            <p class="lh-base"><?= $item['analyseRentabilite'] ?></p>
                        </div>
                        <div class="mt-2 border rounded rounded-2 p-2">
                            <h4 class="fs-6">7. CONCLUSIONS GENERALES POUR L'ANALYSTE</h4>
                            <p class="lh-base"><?= $item['conclusionsAnalyste'] ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="report1Modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header justify-content-between">
                    <div style="float: right">
                        <button data-bs-dismiss="modal" class="btn btn-sm" >x</button>
                    </div>
                </div>
                <div class="modal-body">
                    <p id="description"></p>
                    <form action="{{ route('analyste.dossier.set.analyse') }}" method="post">
                        @csrf
                        <input type="hidden" value="{{ $item['id'] }}" name="dossier_id">
                        <input type="hidden" id="sequence" name="sequence">
                        <div class="mt-2">
                            <x-quill :name="'content'"></x-quill>
                        </div>
                        <div class="mt-1">
                            <button type="submit" class="btn-primary btn">ENREGISTRER</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const labels = [
            "Brèves données générales actualisées sur l'emprunteur / Diagnostic des options stratégiques et politiques (Vision du ou des promoteur(s), aspect juridique, répartition du capital, organigramme -à joindre éventuellement en annexe-, analysteistrateurs, équipe de direction, aspects économiques et commerciaux macro et méso, relations avec d'autres Institutions financières ou Directions de l’établissement, principaux banquiers, auditeurs).",
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

    </script>

@endsection


