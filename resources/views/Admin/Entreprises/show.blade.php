@extends('Layouts.admin')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">ANGARA</a></li>
       <li class="breadcrumb-item"><a href="#">entreprises</a></li>
       <li class="breadcrumb-item active" aria-current="page">{{ $item['name'] }}</li>
    </ol>
 </nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">{{ $item['name'] }}</h5>
        <p class="lead">Details sur l'entreprise</p>
    </div>
@endsection



@section('content')
    <div class="d-flex gap-1">
        <div class="card w-25">
            <div class="card-body">
                <h5>entreprise : {{ $item['name'] }}</h5>
            </div>
        </div>
        <div class="card w-75">
            <div class="card-body">
                <h5>Liste des soumissions</h5>
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>DOSSIER</th>
                            <th>PROGRAMME</th>
                            <th>NOTE</th>
                            <th>ETAT</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($item['dossiers'] as $dossier)
                            <tr>
                                <td>{{ $dossier['name'] }}</td>
                                <td>{{ $dossier['programme']['name'] }}</td>
                                <td>{{ $dossier['note'] }}</td>
                                <td> <span class="badge bg-{{ $dossier['state']['status']?'success':'warning' }}">{{ $dossier['state']['name'] }} </span></td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-xs btn-outline-primary dropdown-toggle hstack gap-2" data-bs-toggle="dropdown" aria-expanded="false">
                                           Actions
                                           <span class="vr"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            @if($dossier['state']['status'])
                                                <li><a class="dropdown-item" href="{{ route('admin.dossier.show',$dossier['id']) }}">Afficher</a></li>
                                            @else
                                                <li><a class="dropdown-item" href="{{ route('admin.dossier.instruction.create',$dossier['id']) }}">Demarrer l'instruction</a></li>
                                            @endif

                                        </ul>
                                     </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div style="height: 300px; overflow:scroll" class="mt-2">
        <div class="card">
            <div class="card-body">
                <h4>GRILLE DES ENGAGEMENTS</h4>
                <div class="alert alert-info d-flex align-items-center justify-content-between">
                    <div>
                        <strong>Grille des engagements</strong><br>
                        <small class="text-muted">La grille des engagements consolidée par produit, par banque/EMF et par partenaire financier est désormais disponible sur une page dédiée.</small>
                    </div>
                    @if (! empty($item['token']))
                        <a class="btn btn-primary"
                           href="{{ route('engagements.show', $item['token']) }}">
                            Ouvrir la grille
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>


@endsection
