@extends('Layouts.gestionnaire')

@section('title', 'Espace chef de filière')

@section('page-header')
    <div>
        <h1 class="h3 mb-0">Espace chef de filière</h1>
        <p class="text-muted mb-0">Qualification des clients et identification des besoins.</p>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-body">
                <p class="mb-2">Clients promus en attente de qualification ou d'affectation : <strong>{{ $clientCount }}</strong></p>
                <a href="{{ route('chef-filiere.qualifications.index') }}" class="btn btn-primary btn-sm">Ouvrir les qualifications</a>
            </div>
        </div>
    </div>
@endsection
