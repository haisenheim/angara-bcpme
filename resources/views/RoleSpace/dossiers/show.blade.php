@extends(match ($space['route'] ?? '') {
    'respexp' => 'Layouts.respexp',
    'juridique' => 'Layouts.juridique',
    'analyste-juridique' => 'Layouts.analyste-juridique',
    'reng' => 'Layouts.reng',
    'analyste-credit' => 'Layouts.analyste-credit',
    'analyste-risques' => 'Layouts.analyste-risques',
    'rerx' => 'Layouts.rerx',
    default => 'Layouts.app',
})

@if(in_array($space['route'] ?? '', ['respexp', 'juridique', 'analyste-juridique', 'reng', 'analyste-credit', 'rerx', 'analyste-risques'], true))
@push('styles')
@include('partials.summernote-fr-styles')
@endpush
@endif

@section('title', 'Dossier - '.$space['title'])

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route($space['route'].'.dashboard') }}">Tableau de bord</a></li>
        <li class="breadcrumb-item"><a href="{{ route($space['route'].'.dossiers.index') }}">Dossiers</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($dossier->programme?->name ?? 'Dossier', 42) }}</li>
    </ol>
</nav>
@endsection

@section('actions')
<div class="dropdown">
    <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" id="dossierActionsDropdown">
        <i class="demo-psi-dot-vertical me-1"></i> Actions
    </button>
    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dossierActionsDropdown">
        <li><a class="dropdown-item" href="{{ route($space['route'].'.dossiers.index') }}"><i class="demo-pli-arrow-left me-2"></i>Retour aux dossiers</a></li>
        @if($dossier->entreprise)
            <li><a class="dropdown-item" href="{{ route($space['route'].'.entreprises.show', $dossier->entreprise->token) }}"><i class="demo-pli-building me-2"></i>Voir l'entreprise</a></li>
        @endif
        @if(($space['route'] ?? '') === 'respexp')
            <li><hr class="dropdown-divider"></li>
            @if(isset($analystesExploitation) && $analystesExploitation->isNotEmpty())
                <li>
                    <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modalAffecterAnalyste">
                        @if($dossier->analyste_id)
                            <i class="demo-psi-user me-2"></i>Réaffecter à un autre analyste
                        @else
                            <i class="demo-psi-user me-2"></i>Affecter à un analyste financier
                        @endif
                    </button>
                </li>
            @else
                <li>
                    <span class="dropdown-item disabled text-muted small" tabindex="-1">
                        Aucun analyste financier (profil {{ (int) config('angara.role_analyste_financier', 17) }}) disponible pour l’affectation.
                    </span>
                </li>
            @endif
        @endif
        @if(($space['route'] ?? '') === 'juridique' && ! $dossier->isSubmittedToEngagementsFromJuridique() && ! $dossier->isJuridiqueAnalysteAvisSubmittedToReju())
            <li><hr class="dropdown-divider"></li>
            @if(isset($analystesJuridique) && $analystesJuridique->isNotEmpty())
                <li>
                    <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modalAffecterAnalysteJuridique">
                        <i class="demo-psi-user me-2"></i>{{ $dossier->juridique_analyste_user_id ? 'Réaffecter un analyste juridique' : 'Affecter un analyste juridique' }}
                    </button>
                </li>
            @else
                <li>
                    <span class="dropdown-item disabled text-muted small" tabindex="-1">
                        Aucun analyste juridique (profil {{ (int) config('angara.role_analyste_juridique', 19) }}) disponible.
                    </span>
                </li>
            @endif
        @endif
        @if(($space['route'] ?? '') === 'reng' && ! $dossier->isSubmittedToRisquesFromReng())
            <li><hr class="dropdown-divider"></li>
            @if(isset($analystesCredit) && $analystesCredit->isNotEmpty() && ! $dossier->isRengAnalysteCreditSubmittedToReng())
                <li>
                    <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modalAffecterAnalysteCredit">
                        <i class="demo-psi-user me-2"></i>{{ $dossier->reng_analyste_credit_user_id ? 'Réaffecter un analyste crédit' : 'Affecter un analyste crédit' }}
                    </button>
                </li>
            @elseif($dossier->isRengAnalysteCreditSubmittedToReng())
                <li>
                    <span class="dropdown-item disabled text-muted small" tabindex="-1">L’affectation analyste crédit est verrouillée après soumission.</span>
                </li>
            @else
                <li>
                    <span class="dropdown-item disabled text-muted small" tabindex="-1">
                        Aucun analyste crédit (profil {{ (int) config('angara.role_analyste_credit', 20) }}) disponible.
                    </span>
                </li>
            @endif
        @endif
        @if(($space['route'] ?? '') === 'rerx' && ! $dossier->isSubmittedToDirectionFromRerx())
            <li><hr class="dropdown-divider"></li>
            @if(isset($analystesRisques) && $analystesRisques->isNotEmpty() && ! $dossier->isRerxAnalysteRisquesSubmittedToRerx())
                <li>
                    <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modalAffecterAnalysteRisques">
                        <i class="demo-psi-user me-2"></i>{{ $dossier->rerx_analyste_risques_user_id ? 'Réaffecter un analyste risques' : 'Affecter un analyste risques' }}
                    </button>
                </li>
            @elseif($dossier->isRerxAnalysteRisquesSubmittedToRerx())
                <li>
                    <span class="dropdown-item disabled text-muted small" tabindex="-1">L’affectation analyste risques est verrouillée après soumission.</span>
                </li>
            @else
                <li>
                    <span class="dropdown-item disabled text-muted small" tabindex="-1">
                        Aucun analyste risques (profil {{ (int) config('angara.role_analyste_risques', 18) }}) disponible.
                    </span>
                </li>
            @endif
        @endif
    </ul>
</div>
@endsection

@section('page-header')
<div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <h5 class="page-title mb-0">Dossier — {{ $dossier->programme?->name ?? '—' }}</h5>
    </div>
    <p class="text-body-secondary mb-0 mt-1">{{ $dossier->entreprise?->name ?? 'Entreprise non renseignée' }}</p>
    @if(in_array($space['route'] ?? '', ['respexp', 'juridique'], true) && $dossier->exploitation_analyste_assigned_at)
        <p class="small text-muted mb-0 mt-2 border-start border-3 border-primary ps-2">
            <span class="text-uppercase fw-semibold">Cotation du dossier</span> —
            le {{ $dossier->exploitation_analyste_assigned_at->format('d/m/Y') }} à {{ $dossier->exploitation_analyste_assigned_at->format('H:i') }}
            @if($dossier->exploitationAnalysteAssignedBy)
                par <strong>{{ $dossier->exploitationAnalysteAssignedBy->name }}</strong>
            @else
                <span class="text-muted">(auteur non renseigné)</span>
            @endif
        </p>
    @endif
    @if(in_array($space['route'] ?? '', ['reng', 'analyste-credit'], true) && $dossier->reng_analyste_credit_assigned_at && $dossier->reng_analyste_credit_user_id)
        <p class="small text-muted mb-0 mt-2 border-start border-3 border-success ps-2">
            <span class="text-uppercase fw-semibold">Analyste crédit</span> —
            affectation le {{ $dossier->reng_analyste_credit_assigned_at->format('d/m/Y H:i') }}
            @if($dossier->rengAnalysteCreditAssignedBy)
                par <strong>{{ $dossier->rengAnalysteCreditAssignedBy->name }}</strong>
            @endif
        </p>
    @endif
    @if(($space['route'] ?? '') === 'rerx' && $dossier->rerx_analyste_risques_assigned_at && $dossier->rerx_analyste_risques_user_id)
        <p class="small text-muted mb-0 mt-2 border-start border-3 border-warning ps-2">
            <span class="text-uppercase fw-semibold">Analyste risques</span> —
            affectation le {{ $dossier->rerx_analyste_risques_assigned_at->format('d/m/Y H:i') }}
            @if($dossier->rerxAnalysteRisquesAssignedBy)
                par <strong>{{ $dossier->rerxAnalysteRisquesAssignedBy->name }}</strong>
            @endif
        </p>
    @endif
</div>
@endsection

@section('content')
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>
            </div>
        @endif

        @if(in_array($space['route'] ?? '', ['respexp', 'juridique', 'analyste-juridique', 'reng', 'analyste-credit', 'rerx', 'analyste-risques', 'dg', 'dga'], true))
            @if(($space['route'] ?? '') === 'respexp' && isset($analystesExploitation) && $analystesExploitation->isNotEmpty())
                @include('RoleSpace.dossiers.partials.respexp_assign_analyste_modal')
            @endif
            @if(($space['route'] ?? '') === 'juridique' && isset($analystesJuridique) && $analystesJuridique->isNotEmpty() && ! $dossier->isSubmittedToEngagementsFromJuridique() && ! $dossier->isJuridiqueAnalysteAvisSubmittedToReju())
                @include('RoleSpace.dossiers.partials.juridique_assign_analyste_modal')
            @endif
            @if(($space['route'] ?? '') === 'reng' && isset($analystesCredit) && $analystesCredit->isNotEmpty() && ! $dossier->isSubmittedToRisquesFromReng() && ! $dossier->isRengAnalysteCreditSubmittedToReng())
                @include('RoleSpace.dossiers.partials.reng_assign_analyste_credit_modal')
            @endif
            @if(($space['route'] ?? '') === 'rerx' && isset($analystesRisques) && $analystesRisques->isNotEmpty() && ! $dossier->isSubmittedToDirectionFromRerx() && ! $dossier->isRerxAnalysteRisquesSubmittedToRerx())
                @include('RoleSpace.dossiers.partials.rerx_assign_analyste_risques_modal')
            @endif
            @include('RoleSpace.dossiers.partials.respexp_dossier_hub')
            @if(($space['route'] ?? '') === 'analyste-credit')
                @include('RoleSpace.dossiers.partials.dossier_instruction_history')
            @endif
            @if(in_array($space['route'] ?? '', ['juridique', 'analyste-juridique'], true))
                @include('RoleSpace.dossiers.partials.juridique_instruction_workflow')
            @endif
            @if(in_array($space['route'] ?? '', ['reng', 'analyste-credit', 'rerx', 'analyste-risques', 'dg', 'dga'], true))
                @include('RoleSpace.dossiers.partials.reng_instruction_workflow')
            @endif
            @if(in_array($space['route'] ?? '', ['rerx', 'analyste-risques', 'dg', 'dga'], true))
                @include('RoleSpace.dossiers.partials.rerx_risques_workflow')
            @endif
        @else
            <div class="row g-3">
                <div class="col-md-3"><div class="card shadow-sm border-0"><div class="card-body"><small class="text-muted d-block">Programme</small><strong>{{ $dossier->programme?->name ?? '—' }}</strong></div></div></div>
                <div class="col-md-3"><div class="card shadow-sm border-0"><div class="card-body"><small class="text-muted d-block">Analyste</small><strong>{{ $dossier->analyste?->name ?? '—' }}</strong></div></div></div>
                <div class="col-md-3"><div class="card shadow-sm border-0"><div class="card-body"><small class="text-muted d-block">Gestionnaire</small><strong>{{ $dossier->gestionnaire?->name ?? '—' }}</strong></div></div></div>
                <div class="col-md-3"><div class="card shadow-sm border-0"><div class="card-body"><small class="text-muted d-block">État</small><strong>{{ $dossier->status['name'] ?? '—' }}</strong></div></div></div>
            </div>

            <div class="row g-3 mt-1">
                <div class="col-md-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-transparent"><strong>Indicateurs et réponses</strong></div>
                        <div class="card-body">
                            <p class="mb-2">Indicateurs financiers : <strong>{{ $dossier->indicateurs->count() }}</strong></p>
                            <p class="mb-0">Réponses d'instruction : <strong>{{ $dossier->reponses->count() }}</strong></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-transparent"><strong>Contexte entreprise</strong></div>
                        <div class="card-body">
                            <p class="mb-2">Entreprise : <strong>{{ $dossier->entreprise?->name ?? '—' }}</strong></p>
                            <p class="mb-2">Agence : <strong>{{ $dossier->agence?->name ?? '—' }}</strong></p>
                            <p class="mb-0">Note calculée : <strong>{{ number_format((float) ($dossier->note ?? 0), 2, ',', ' ') }}</strong></p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@section('scripts')
@if(in_array($space['route'] ?? '', ['respexp', 'juridique', 'analyste-juridique', 'reng', 'analyste-credit', 'rerx', 'analyste-risques'], true))
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/lang/summernote-fr-FR.min.js"></script>
<script>
(function ($) {
    var toolbarFull = [
        ['style', ['style']],
        ['font', ['bold', 'italic', 'underline', 'clear']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['insert', ['link', 'hr']],
        ['view', ['codeview']]
    ];
    function baseOptions(height, toolbar) {
        return {
            lang: 'fr-FR',
            height: height,
            dialogsInBody: true,
            toolbar: toolbar,
            callbacks: {
                onChange: function (contents) {
                    $(this).val(contents);
                }
            }
        };
    }
    function syncSummernoteToTextarea($ta) {
        if ($ta.length && $ta.next('.note-editor').length) {
            $ta.val($ta.summernote('code'));
        }
    }
    $(document).ready(function () {
        if ($('#exploitation_avis_credit').length) {
            $('#exploitation_avis_credit').summernote($.extend({}, baseOptions(280, toolbarFull), {
                placeholder: 'Rédigez l’avis de crédit…'
            }));
            $('#avis-credit-form').on('submit', function () {
                syncSummernoteToTextarea($('#exploitation_avis_credit'));
            });
        }
        $('.js-summernote-juridique').each(function () {
            var $ta = $(this);
            if ($ta.next('.note-editor').length) return;
            $ta.summernote($.extend({}, baseOptions(260, toolbarFull), {
                placeholder: 'Rédigez votre avis…'
            }));
        });
        $('#form-analyste-juridique-soumettre').on('submit', function () {
            syncSummernoteToTextarea($('#juridique_analyste_avis'));
        });
        $('form[action*="/juridique/dossiers/"][action*="responsable-avis"]').on('submit', function () {
            syncSummernoteToTextarea($('#juridique_responsable_avis'));
        });
        $('form[action*="/reng/dossiers/"][action*="responsable-avis"]').on('submit', function () {
            syncSummernoteToTextarea($('#reng_responsable_avis'));
        });
        $('form[action*="/rerx/dossiers/"][action*="responsable-avis"]').on('submit', function () {
            syncSummernoteToTextarea($('#rerx_responsable_avis'));
        });
        $('.js-summernote-reng').each(function () {
            var $ta = $(this);
            if ($ta.next('.note-editor').length) return;
            $ta.summernote($.extend({}, baseOptions(240, toolbarFull), {
                placeholder: '…'
            }));
        });
        $('#form-analyste-credit-reng').on('submit', function () {
            syncSummernoteToTextarea($('#reng_contre_analyse'));
            syncSummernoteToTextarea($('#reng_analyste_credit_avis'));
        });
        $('.js-summernote-rerx').each(function () {
            var $ta = $(this);
            if ($ta.next('.note-editor').length) return;
            $ta.summernote($.extend({}, baseOptions(240, toolbarFull), {
                placeholder: '…'
            }));
        });
        $('#form-analyste-risques-rerx').on('submit', function () {
            syncSummernoteToTextarea($('#rerx_analyse_risques'));
            syncSummernoteToTextarea($('#rerx_analyste_risques_avis'));
        });
        @if($errors->has('analyste_user_id'))
        (function () {
            var el = document.getElementById('modalAffecterAnalyste');
            if (el && window.bootstrap) {
                bootstrap.Modal.getOrCreateInstance(el).show();
            }
        })();
        @endif
        @if($errors->has('juridique_analyste_user_id'))
        (function () {
            var el = document.getElementById('modalAffecterAnalysteJuridique');
            if (el && window.bootstrap) {
                bootstrap.Modal.getOrCreateInstance(el).show();
            }
        })();
        @endif
        @if($errors->has('reng_analyste_credit_user_id'))
        (function () {
            var el = document.getElementById('modalAffecterAnalysteCredit');
            if (el && window.bootstrap) {
                bootstrap.Modal.getOrCreateInstance(el).show();
            }
        })();
        @endif
        @if($errors->has('rerx_analyste_risques_user_id'))
        (function () {
            var el = document.getElementById('modalAffecterAnalysteRisques');
            if (el && window.bootstrap) {
                bootstrap.Modal.getOrCreateInstance(el).show();
            }
        })();
        @endif
    });
})(jQuery);
</script>
@endif
@endsection
