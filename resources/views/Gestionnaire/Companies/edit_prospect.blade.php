@extends('Layouts.gestionnaire')

@section('title', 'Modifier prospect — ' . Str::limit($item->name, 30))
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('gestionnaire.dashboard') }}">Angara</a></li>
        <li class="breadcrumb-item"><a href="{{ route('gestionnaire.entreprises.prospects') }}">Prospects</a></li>
        <li class="breadcrumb-item"><a href="{{ route('gestionnaire.entreprises.show', $item->token) }}">{{ Str::limit($item->name, 40) }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">Modifier</li>
    </ol>
</nav>
@endsection

@section('actions')
    <x-page-actions-dropdown>
        <li>
            <a href="{{ route('gestionnaire.entreprises.show', $item->token) }}" class="dropdown-item"><i class="demo-pli-arrow-left me-2"></i>Retour à la fiche</a>
        </li>
    </x-page-actions-dropdown>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Compléter le prospect</h5>
        <p class="text-body-secondary mb-0">Tant que le dossier n’est pas soumis, vous pouvez enrichir la fiche. Après soumission, les avis juridique et conformité seront saisis par les responsables désignés.</p>
    </div>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-xl-11">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-lg-5">
                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @php
                        $prospectSubmittedAt = $item->prospect_submitted_at;
                        if (is_string($prospectSubmittedAt) && trim($prospectSubmittedAt) !== '') {
                            $prospectSubmittedAt = \Illuminate\Support\Carbon::parse($prospectSubmittedAt);
                        }
                    @endphp
                    @if($prospectSubmittedAt)
                        <div class="alert alert-warning">Ce prospect a été soumis le {{ $prospectSubmittedAt?->format('d/m/Y H:i') }} — la fiche n’est plus modifiable ici.</div>
                    @else
                    <form action="{{ route('gestionnaire.entreprises.save') }}" method="post" id="form-prospect-edit" novalidate>
                        @csrf
                        <input type="hidden" name="token" value="{{ $item->token }}">
                        @include('Gestionnaire.Companies.partials.prospect_form_fields', ['item' => $item])
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
