@extends('../Layouts.tenant.payeur')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">KeKa</a></li>
       <li class="breadcrumb-item"><a href="#">Entreprots</a></li>
       <li class="breadcrumb-item active" aria-current="page">{{ $item->name }}</li>
    </ol>
 </nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">{{ $item->name }}</h5>
        <p class="lead">Details sur l'entrepot</p>
    </div>
@endsection

@section('content')
    <div class="d-flex gap-1">
        <div class="card w-25">
            <div class="card-body">
                <h5>ENTREPROT : {{ $item->name }}</h5>
                <h6>COOPERATIVE : {{ $item->cooperative?$item->cooperative->name:'-' }}</h6>
            </div>
        </div>
        <div class="card w-75">
            <div class="card-body">

            </div>
        </div>
    </div>

@endsection
