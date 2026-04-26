@extends('Layouts.gestionnaire')

@section('title', 'Nouveau prospect')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('gestionnaire.dashboard') }}">Angara</a></li>
        <li class="breadcrumb-item"><a href="{{ route('gestionnaire.entreprises.index') }}">Entreprises</a></li>
        <li class="breadcrumb-item"><a href="{{ route('gestionnaire.entreprises.prospects') }}">Prospects</a></li>
        <li class="breadcrumb-item active" aria-current="page">Nouveau prospect</li>
    </ol>
</nav>
@endsection

@section('actions')
    <x-page-actions-dropdown>
        <li>
            <a href="{{ route('gestionnaire.entreprises.prospects') }}" class="dropdown-item"><i class="demo-pli-arrow-left me-2"></i>Retour à la liste</a>
        </li>
    </x-page-actions-dropdown>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Nouveau prospect</h5>
    </div>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-xl-11">
            <div class="card border-0 shadow-sm angara-filter-card">
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

                    <form action="{{ route('gestionnaire.entreprises.prospects.store') }}" method="post" id="form-prospect" novalidate>
                        @csrf
                        @include('Gestionnaire.Companies.partials.prospect_form_fields')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
