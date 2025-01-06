@extends('Layouts.ca')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">Angara</a></li>
       <li class="breadcrumb-item"><a href="#">Utilisateurs</a></li>
       <li class="breadcrumb-item active" aria-current="page">Liste des comptes utilisateurs</li>
    </ol>
 </nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Comptes utilisateurs</h5>
        <p class="lead">Liste des comptes utilisateurs</p>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div style="max-height: 50vh; overflow: scroll;">
                <table  class="table table-hover table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Telephone</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Agence</th>
                            <th>Direction</th>
                            <td>Statut</td>
                            <td>

                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->phone }}</td>
                                <td>{{ $item->email }}</td>
                                <th>{{ $item->role->name }}</th>
                                <td>{{ $item->agence?$item->agence->name:'-'  }}</td>
                                <td>{{ $item->representation?->name  }}</td>
                                <td><span class="badge bg-{{ $item->status['color'] }}">{{ $item->status['name'] }}</span></td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-xs btn-outline-primary dropdown-toggle hstack gap-2" data-bs-toggle="dropdown" aria-expanded="false">
                                           Actions
                                           <span class="vr"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            @if($item->active)
                                                <li><a class="dropdown-item" href="{{ route('ca.user.disable',$item->token) }}">Verrouiller</a></li>
                                            @else
                                                <li><a class="dropdown-item" href="{{ route('ca.user.enable',$item->token) }}">Activer</a></li>
                                            @endif

                                            @if($item->role_id==4)
                                                <li><a data-id="{{ $item->id }}" data-bs-target="#depModal" data-bs-toggle="modal" class="dropdown-item btn-user" href="#">Affecter</a></li>
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


    <script>
        $(document).ready(function(){


        })
    </script>
@endsection
