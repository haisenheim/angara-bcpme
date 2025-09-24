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
 <link rel="stylesheet" type="text/css" href="{{ asset('jquery-easyui/themes/default/easyui.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('jquery-easyui/themes/icon.css') }}">
<script type="text/javascript" src="{{ asset('jquery-easyui/jquery.easyui.min.js') }}"></script>
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
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Village</th>
                        <th>Arrondissement</th>
                        <th>Departement</th>
                        <th>Region</th>
                        <th>Actions</th>
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
                                <a href="{{ route('admin.villages.show',$item->id) }}" class="btn btn-primary btn-sm"><i class="demo-pli-eye me-2 fs-5"></i> Voir</a>
                                <a href="{{ route('admin.villages.edit',$item->id) }}" class="btn btn-warning btn-sm"><i class="demo-pli-edit me-2 fs-5"></i> Modifier</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


@endsection
