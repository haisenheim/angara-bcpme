@extends('../Layouts.tenant.agent')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">Angara</a></li>
       <li class="breadcrumb-item"><a href="#">Entrepots</a></li>
       <li class="breadcrumb-item active" aria-current="page">Liste des entrepots</li>
    </ol>
 </nav>
@endsection



@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Entrepots</h5>
        <p class="lead">Liste de tous les entrepots du bassin</p>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Intitule</th>
                        <th>Stock en Kg</th>
                        <th>Stock en tonnes</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td><a href="{{ route('agent.entrepots.show',$item->token) }}">{{ $item->name }}</a></td>
                            <td>{{ number_format($item->stock,0,',','.') }}</td>
                            <td>{{ number_format($item->stock/1000,2,',','.') }}</td>
                            <td>{{ $item->latitude }}</td>
                            <td>{{ $item->longitude }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <style>
        .form-group{
            margin-top: 1rem;
        }
    </style>
@endsection
