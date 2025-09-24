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
        <h5 class="page-title mb-0 mt-2">Liste des agences</h5>
        <p class="lead">Liste de toutes les agences du pays</p>
    </div>
@endsection



@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table table-sm table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Direction</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
            <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->representation?$item->representation->name:'-' }}</td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-xs btn-light dropdown-toggle hstack gap-3" data-bs-toggle="dropdown" aria-expanded="false">
                                    Actions
                                    <span class="vr"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('admin.agences.show',$item->id) }}"><i class="demo-pli-eye me-2 fs-5"></i> Voir</a></li>
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
