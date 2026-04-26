@extends('Layouts.admin')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">Cogelo</a></li>
       <li class="breadcrumb-item"><a href="#">Caisses</a></li>
       <li class="breadcrumb-item active" aria-current="page">{{ $item->name }}</li>
    </ol>
 </nav>
@endsection

@section('actions')
    <x-page-actions-dropdown menu-class="dropdown-menu dropdown-menu-end border shadow-sm py-2">
        <li><a data-bs-toggle="modal" data-bs-target="#addModal" class="dropdown-item" href="#">Parametrer le compte principal</a></li>
        <li><a data-bs-toggle="modal" data-bs-target="#addModal2" class="dropdown-item" href="#">Associer un compte d'operation</a></li>
    </x-page-actions-dropdown>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">{{ $item->name }}</h5>
        <p class="lead">Details sur la caisse</p>
    </div>
@endsection

@section('content')
    <div class="d-flex gap-1">
        <div class="card w-25">
            <div class="card-body">
                <h5>CAISSE : {{ $item->name }}</h5>
                <h5>COMPTE : {{ $item->compte }}</h5>
                <h6>AGENCE : {{ $item->agence->name }} / {{ $item->ville->name }}</h6>
                <h6>OPERATEUR : {{ $item->user?$item->user->name:'-' }}</h6>
                <fieldset>
                    <legend>Comptes des operations</legend>
                    @foreach($item->comptes as $cmp)
                        <span class="badge bg-primary" title="{{ $cmp->name }}">{{ $cmp->code }}</span>
                    @endforeach
                </fieldset>
            </div>
        </div>
        <div class="card w-75">
            <div class="card-body">
                
            </div>
        </div>
    </div>

    <div class="modal fade" id="addModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header justify-content-between">
                    <h5 class="modal-title">Parametrage du compte principal</h5>
                    <div style="float: right">
                        <button data-bs-dismiss="modal" class="btn btn-sm" >x</button>
                    </div>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" action="{{ route('admin.caisse.set.compte') }}" method="post">
                        @csrf
                        <input type="hidden" name="caisse_id" value="{{ $item->id }}">
                        <div class="mt-3">
                            <label for="">Compte</label>
                            <input type="texte" name="compte" placeholder="Saisir le numro de compte ici ..." class="form-control">
                        </div>
                        <div class="mt-5">
                            <button type="submit" class="btn-primary btn">ENREGISTRER</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addModal2">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header justify-content-between">
                    <h5 class="modal-title">Associer un compte d'operation</h5>
                    <div style="float: right">
                        <button data-bs-dismiss="modal" class="btn btn-sm" >x</button>
                    </div>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" action="{{ route('admin.caisse.add.compte') }}" method="post">
                        @csrf
                        <input type="hidden" name="caisse_id" value="{{ $item->id }}">
                        <div class="mt-3">
                            <label for="">Compte</label>
                            <select name="compte_id" required id="" class="form-control">
                                <option value="">Choix du compte ...</option>
                                @foreach($comptes as $cpt)
                                    <option value="{{ $cpt->id }}">{{ $cpt->code }} - {{ $cpt->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-5">
                            <button type="submit" class="btn-primary btn">ENREGISTRER</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
