@extends('Layouts.analyste-credit')

@push('styles')
@include('partials.summernote-fr-styles')
@endpush

@section('title', 'État des engagements — '.$entreprise->name)

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('analyste-credit.dashboard') }}">Tableau de bord</a></li>
        <li class="breadcrumb-item"><a href="{{ route('analyste-credit.entreprises.index') }}">Entreprises</a></li>
        <li class="breadcrumb-item"><a href="{{ route('analyste-credit.entreprises.show', $entreprise->token) }}">{{ Str::limit($entreprise->name, 36) }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">État des engagements</li>
    </ol>
</nav>
@endsection

@section('actions')
    <a href="{{ route('analyste-credit.entreprises.show', $entreprise->token) }}" class="btn btn-sm btn-outline-secondary">
        <i class="demo-pli-arrow-left me-1"></i> Retour à l’entreprise
    </a>
@endsection

@section('page-header')
<div>
    <h5 class="page-title mb-0">État des engagements du client</h5>
    <p class="text-body-secondary mb-0 mt-1">{{ $entreprise->name }}</p>
    <p class="small text-muted mb-0 mt-2">Mise à jour par dossier d’instruction qui vous est affecté. Les textes sont enregistrés sur chaque dossier.</p>
</div>
@endsection

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif

    <form method="post" action="{{ route('analyste-credit.entreprises.etat-engagements.update', $entreprise->token) }}" id="form-etat-engagements-ac">
        @csrf
        @foreach($dossiers as $dossier)
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <strong>{{ $dossier->programme?->name ?? 'Dossier #'.$dossier->id }}</strong>
                    <a href="{{ route('analyste-credit.dossiers.show', $dossier->token) }}" class="btn btn-sm btn-outline-primary float-end">Ouvrir le dossier</a>
                </div>
                <div class="card-body">
                    <label class="form-label" for="etat_{{ $dossier->token }}">État des engagements du client</label>
                    <textarea name="etat[{{ $dossier->token }}]" id="etat_{{ $dossier->token }}" class="form-control js-summernote-etat-ac" rows="10">{!! old('etat.'.$dossier->token, $dossier->reng_etat_engagements_client) !!}</textarea>
                </div>
            </div>
        @endforeach
        <button type="submit" class="btn btn-primary">Enregistrer</button>
    </form>
</div>
@endsection

@section('scripts')
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
    $(document).ready(function () {
        $('.js-summernote-etat-ac').each(function () {
            var $ta = $(this);
            if ($ta.next('.note-editor').length) return;
            $ta.summernote({
                lang: 'fr-FR',
                height: 220,
                dialogsInBody: true,
                toolbar: toolbarFull,
                placeholder: 'Décrivez l’état des engagements…',
                callbacks: {
                    onChange: function (contents) { $(this).val(contents); }
                }
            });
        });
        $('#form-etat-engagements-ac').on('submit', function () {
            $('.js-summernote-etat-ac').each(function () {
                var $ta = $(this);
                if ($ta.next('.note-editor').length) {
                    $ta.val($ta.summernote('code'));
                }
            });
        });
    });
})(jQuery);
</script>
@endsection
