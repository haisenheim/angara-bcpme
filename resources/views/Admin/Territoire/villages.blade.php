@extends('Layouts.admin')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">Angara</a></li>
       <li class="breadcrumb-item"><a href="#">Organisation du territoire</a></li>
       <li class="breadcrumb-item active" aria-current="page">Liste des localites</li>
    </ol>
 </nav>

@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Organisation du territoire</h5>
        <p class="lead">Liste de toutes les communes</p>
    </div>
@endsection



@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table table-sm table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Village</th>
                        <th>Arrondissement</th>
                        <th>Departement</th>
                        <th>Region</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->arrondissement?$item->arrondissement->name:'-' }}</td>
                            <td>{{ $item->departement?$item->departement->name:'-' }}</td>
                            <td>{{ $item->region?$item->region->name:'-' }}</td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-xs btn-light dropdown-toggle hstack gap-3" data-bs-toggle="dropdown" aria-expanded="false">
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('admin.villages.show',$item->id) }}"><i class="demo-pli-eye me-2 fs-5"></i> Voir</a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.villages.edit',$item->id) }}"><i class="demo-pli-edit me-2 fs-5"></i> Modifier</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


@endsection
