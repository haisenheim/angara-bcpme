@extends('../Layouts.tenant.admin')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">Angara</a></li>
       <li class="breadcrumb-item"><a href="#">Adhérents</a></li>
       <li class="breadcrumb-item active" aria-current="page">Liste des adhérents</li>
    </ol>
 </nav>
@endsection

@section('actions')
    <a href="#" data-bs-target="#addModal" data-bs-toggle="modal" class="btn btn-primary btn-sm"><i class="demo-pli-add me-2 fs-5"></i> Créer un adhérent</a>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Adhérents </h5>
        <p class="lead">Liste des adhérents  </p>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table table-sm table-striped table-bordered">
                <thead>
                    <tr>
                        <th>NOM</th>
                        <th>PRENOM</th>
                        <th>AGE</th>
                        <th>VILLAGE</th>
                        <th>TELEPHONE</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td>{{ $item->last_name }}</td>
                            <td>{{ $item->first_name }}</td>
                            <td>{{ $item->age }} ans</td>
                            <td>{{ $item->village?$item->village->name:'-' }}</td>
                            <td>{{ $item->phone }}</td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-xs btn-outline-primary dropdown-toggle hstack gap-2" data-bs-toggle="dropdown" aria-expanded="false">
                                       Actions
                                       <span class="vr"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                       <li><a class="dropdown-item" href="{{ route('admin.members.show',$item->token) }}">Afficher</a></li>
                                    </ul>
                                 </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="addModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header justify-content-between">
                    <h5 class="modal-title">Nouvel adhérent</h5>
                    <div style="float: right">
                        <button data-bs-dismiss="modal" class="btn btn-sm" >x</button>
                    </div>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" action="{{ route('admin.members.store') }}" method="post">
                        @csrf
                        <fieldset>
                            <legend>Etat civil</legend>
                            <div class="d-flex gap-2 flex-grow">
                                <div class="w-50">
                                    <label for="">NOM</label>
                                    <input required type="text" name="last_name" class="form-control">
                                </div>
                                <div class="w-50">
                                    <label for="">PRENOM</label>
                                    <input required type="text" name="first_name" class="form-control">
                                </div>
                            </div>
                            <div class="d-flex gap-2 mt-3 flex-grow">
                                <div class="w-25">
                                    <label for="">DATE DE NAISSANCE</label>
                                    <input required type="date" name="dtn" class="form-control">
                                </div>
                                <div class="w-50">
                                    <label for="">LIEU DE NAISSANCE</label>
                                    <input required type="text" name="lieu" class="form-control">
                                </div>
                                <div class="w-25">
                                    <label for="">PHOTO</label>
                                    <input type="file" name="photo" class="form-control">
                                </div>
                            </div>
                            <div class="d-flex gap-1 mt-4">
                                <div class="row flex-fill">
                                    <label class="col-sm-3 col-form-label">Genre</label>
                                    <div class="col-sm-9 pt-2">
                                       <div class="form-check form-check-inline">
                                          <input id="_dm-inlineRadio1" class="form-check-input" type="radio" name="male" value="1" checked="">
                                          <label for="_dm-inlineRadio1" class="form-check-label">Homme</label>
                                       </div>
                                       <div class="form-check form-check-inline">
                                          <input id="_dm-inlineRadio2" class="form-check-input" type="radio" name="male" value="0">
                                          <label for="_dm-inlineRadio2" class="form-check-label">Femme</label>
                                       </div>
                                    </div>
                                 </div>
                                <div class="w-25">
                                    <label for="">SITUATION MATRIMONIALE</label>
                                    <select required name="situation_matrimoniale" id="" class="form-control">
                                        <option value="">Choisir un statut ...</option>
                                        <option value="Celibataire">Celibataire</option>
                                        <option value="Veuf/Veuve">Veuf</option>
                                        <option value="Marie(e)">Marie(e)</option>
                                    </select>
                                </div>
                                <div class="w-25">
                                    <label for="">NOMBRE D'ENFANTS</label>
                                    <input type="number" name="nb_enfants" class="form-control">
                                </div>
                            </div>
                            <div class="d-flex gap-3">
                                <div class="w-25">
                                    <label for="">DATE D'ADHESION</label>
                                    <input type="date" name="dt_adhesion" class="form-control">
                                </div>
                                <div class="w-25">
                                    <label for="">&numero; de CNI</label>
                                    <input type="text" name="cni" class="form-control">
                                </div>
                                <div class="w-25">
                                    <label for="">DATE D'EXPIRATION DE LA CNI</label>
                                    <input type="date" name="dt_expiration_cni" class="form-control">
                                </div>
                                <div class="w-25">
                                    <label for="">NIVEAU SCOLAIRE</label>
                                    <select required name="niveau_id" id="" class="form-control">
                                        <option value="">Selectionner ...</option>
                                        @foreach($niveaux as $niv)
                                            <option value="{{ $niv->id }}">{{ $niv->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="mt-3">
                            <legend>AUTRES INFOS</legend>
                            <div class="d-flex gap-2">
                                <div class="w-25">
                                    <label for="">VILLAGE DE RESIDENCE</label>
                                    <select required name="village_id" id="" class="form-control">
                                        <option value="0">Selectionner ...</option>
                                        @foreach($villages as $v)
                                            <option value="{{ $v->id }}">{{ $v->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="w-50">
                                    <label for="">&numero; de compte bancaire</label>
                                    <input type="text" name="compte_bancaire" class="form-control">
                                </div>
                                <div class="w-25">
                                    <label for="">TELEPHONE </label>
                                    <input type="text" name="phone" placeholder="Numero de telephone" class="form-control">
                                </div>
                                <div class="w-25">
                                    <label for="">Email </label>
                                    <input type="email" name="email" placeholder="Adresse electronique" class="form-control">
                                </div>
                            </div>
                        </fieldset>
                        <div class="mt-5">
                            <button type="submit" class="btn-success btn btn-sm"><i class="pli-add"></i> Inserer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
