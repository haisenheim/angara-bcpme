@extends('Layouts.admin')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">Cogelo</a></li>
       <li class="breadcrumb-item"><a href="#">Regions</a></li>
       <li class="breadcrumb-item active" aria-current="page">{{ $region->name }}</li>
    </ol>
 </nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">{{ $region->name }}</h5>
        <p class="lead">Details sur la region</p>
    </div>
@endsection

@section('content')
    <div class="d-flex gap-1">
        <div class="card w-25">
            <div class="card-body">
                <h5>REGION : {{ $region->name }}</h5>
                <h6>ABBREVIATION : {{ $region->abb }}</h6>
            </div>
        </div>
        <div class="card w-75">
            <div class="card-body">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>departement</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($region->departements as $item)
                            <tr>
                                <td>{{ $item->abb }}</td>
                                <td><a href="{{ route('admin.departements.show',$item->id) }}">{{ $item->name }}</a></td>
                                <td></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
