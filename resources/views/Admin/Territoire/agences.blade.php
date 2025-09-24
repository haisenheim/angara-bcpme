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
            <table class="table table-sm table-bordered">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Direction</th>
                    </tr>
                </thead>
            </table>
            <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->representation?$item->representation->name:'-' }}</td>
                    </tr>
                @endforeach
            </tbody>
            </table>
        </div>
    </div>


@endsection
