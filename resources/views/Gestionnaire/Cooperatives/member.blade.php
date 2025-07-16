@extends('Layouts.gestionnaire')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">Angara</a></li>
       <li class="breadcrumb-item"><a href="#">MEMBRES</a></li>
       <li class="breadcrumb-item active" aria-current="page">{{ $item->name }}</li>
    </ol>
 </nav>
@endsection

@section('actions')
<div class="btn-group">
    <button type="button" class="btn btn-xs btn-outline-primary dropdown-toggle hstack gap-2" data-bs-toggle="dropdown" aria-expanded="false">
       Actions
       <span class="vr"></span>
    </button>
    <ul class="dropdown-menu">
        <li><a class="dropdown-item" data-bs-target="#addVergerModal" data-bs-toggle="modal" href="#">Associer un champ</a></li>
    </ul>
 </div>
@endsection


@section('content')
    <div class="row">
       <div class="col-md-3 col-sm-12">
        <div class="card text-bg-light mb-3">
            <div class="card-body">
               <!-- Profile picture and short information -->
               <div class="d-flex align-items-center position-relative py-3 hv-outline-parent hv-outline-inherit">
                  <div class="flex-shrink-0">
                     <img class="img-md rounded-circle hv-oc" src="{{ $item->photo }}" alt="Profile Picture" loading="lazy">
                  </div>
                  <div class="flex-grow-1 ms-3">
                     <a href="#" class="h5 stretched-link btn-link">{{ $item->name }}</a>
                     <p class="m-0">{{ $item->age }} Ans</p>
                  </div>
               </div>
               <!-- END : Profile picture and short information -->

               <div>
                <p>Ne(e) le {{ \Carbon\Carbon::parse($item->dtn)->format('d/m/Y') }} a {{ $item->lieu }}, {{ $item->situation_matrimoniale }} et parent de {{ $item->nb_enfants }} enfant(s)</p>
                <p>CNI &numero; <span class="fw-bold">{{ $item->cni }}</span> expire le <span class="fw-bold">{{ \Carbon\Carbon::parse($item->dt_expiration_cni)->format('d/m/Y') }} </span></p>
               </div>
               <!-- Options buttons -->
               <div class="pt-3 text-center">
                  <div class="d-flex justify-content-center gap-3">
                     <a href="#" class="btn btn-sm btn-light">
                        <i class="d-block demo-psi-consulting fs-3 mb-2"></i> {{ $item->phone }}
                     </a>
                     <a href="#" class="btn btn-sm btn-light">
                        <i class="d-block demo-psi-mail fs-3 mb-2"></i> {{ $item->email }}
                     </a>
                     <a href="#" class="btn btn-sm btn-light">
                        <i class="d-block demo-psi-pen-5 fs-3 mb-2"></i> Mettre a jour
                     </a>
                  </div>
                  <div class="d-flex gap-2 justify-content-between">
                        <div>
                            <p>NOM : <span class="fw-bold">{{ $item->last_name }}</span></p>
                        </div>
                        <div>
                            <p>PRENOM : <span class="fw-bold">{{ $item->first_name }}</span></p>
                        </div>
                  </div>
                    <div class="d-flex gap-2 justify-content-between">
                        <div>
                            <p>Village : <span class="fw-bold">{{ $item->village?$item->village->name:'-' }}</span></p>
                        </div>
                        <div>
                            <p>Niveau : <span class="fw-bold">{{ $item->niveau?$item->niveau->name:'-' }}</span></p>
                        </div>
                    </div>
               </div>
               <!-- END : Options buttons -->
            </div>
            <div class="card-footer">
                Membre depuis le <span class="fw-bold">{{ \Carbon\Carbon::parse($item->dt_adhesion)->format('d/m/Y') }}</span>
            </div>
            <fieldset class="mt-3">
                <legend>Identifiants externe</legend>
                <ul class="list-group">
                    @foreach ($item->liens as $lien)
                        <li class="list-group-item">
                            <div class="d-flex  justify-content-between">
                                <span class="fw-bold text-green">{{ $lien->plateforme?->name }}</span>
                                <div class="text-primary">{{ $lien->user_key }}</div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </fieldset>
         </div>
       </div>
       <div class="col-md-9 col-sm-12 ps-2">
            <div class="">
                <div class="card">
                    <div class="card-body table-responsive">
                        <h6 class="card-title border-bottom pb-2"> <i class="pli-pie-chart-3 fs-3"></i>  PRODUCTION DE LA SAISON</h6>
                        <table class="table table-sm table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>DATE</th>
                                    <th>GAMME</th>
                                    <th>QUANTITE</th>
                                    <th>PRIX UNITAIRE</th>
                                    <th>MONTANT</th>
                                    <th>VERSEMENT</th>
                                    <th>RESTE</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($item->stocks as $part)
                                    <tr>
                                        <td>{{ $part->created_at->format('d/m/Y') }}</td>
                                        <td>{{ $part->gamme->name }}</td>
                                        <td>{{ number_format($part->quantity) }}kg</td>
                                        <td>{{ number_format($part->pu,0,',','.') }}</td>
                                        <td>{{ number_format($part->montant,0,',','.') }}</td>
                                        <td>{{ number_format($part->versements,0,',','.') }}</td>
                                        <td>{{ number_format($part->reste,0,',','.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <h6 class="border-bottom pb-1">Details des versements</h6>
                        <table class="table table-sm table-bordered table-striped table-hover">
                            <thead>
                                <tr class="border-bottom border-3 border-black">
                                    <th>DATE</th>
                                    <th>MODE PAIEMENT</th>
                                    <th>MONTANT</th>
                                    <th>CAISSE</th>
                                    <th>WALLET</th>
                                    <th>NUMERO CIBLE</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($item->paiements->reverse() as $pp)
                                    <tr>
                                        <td>{{ $pp->created_at->format('d/m/Y H:i') }}</td>
                                        <td>{{ $pp->mode?->name }}</td>
                                        <th>{{ number_format($pp->montant,0,',','.') }} FCFA</th>
                                        <td>{{ $pp->caisse?->name }}</td>
                                        <td>{{ $pp->wallet?->name }}</td>
                                        <td>{{ $pp->phone }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer">
                        <h6 class="border-bottom pb-1 mt-3">VERGER</h6>
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr class="border-bottom border-3 border-black">
                                    <th>LIBELLE</th>
                                    <th>SUPERFICIE</th>
                                    <th>ANNEE DE CULTURE / AGE</th>
                                    <th>NOMBRE DE PIEDS/HA</th>
                                    <th>VILLAGE</th>
                                    <th>TYPE</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($item->vergers as $vg)
                                    <tr>
                                        <td><a class="btn-link text-danger fw-bold" href="{{ route('gestionnaire.members.verger.show',["token"=>$vg->token,"tenant_id"=>$tenant_id]) }}">{{$vg->name}}</a></td>
                                        <td>{{ $vg->size}}</td>
                                        <td>{{ $vg->annee }} / {{ $vg->age }}an(s)</td>
                                        <td>{{ $vg->nbph }}</td>
                                        <td>{{ $vg->village?->name}}</td>
                                        <td>-</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
       </div>
    </div>

    <div class="modal fade" id="addVergerModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header justify-content-between">
                    <h5 class="modal-title">Nouveau champ</h5>
                    <div style="float: right">
                        <button data-bs-dismiss="modal" class="btn btn-sm" >x</button>
                    </div>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" action="{{ route('sectoriel.members.verger.add') }}" method="post">
                        @csrf
                        <input type="hidden" name="member_id" value="{{ $item->id }}">
                        <input type="hidden" name="tenant_id" value="{{ $tenant_id }}">
                            <div class="">
                                <div class="mt-3">
                                    <label for="">LIBELLE</label>
                                    <input type="text" required class="form-control" name="name" placeholder="Exemple: Champ angara 1">
                                </div>

                                <div class="mt-3">
                                    <label for="">SUPERFICIE EN ha</label>
                                    <input type="text" required class="form-control" name="size" placeholder="Exemple: 1.5">
                                </div>

                                <div class="mt-3">
                                    <label for="">ANNEE DE CULTURE</label>
                                    <input type="number" required class="form-control" name="annee" placeholder="Exemple: 2006">
                                </div>

                                <div class="mt-3">
                                    <label for="">NOMBRE DE PIEDS / ha</label>
                                    <input type="text" required class="form-control" name="nbph" placeholder="Exemple: 920">
                                </div>

                                <div class="mt-3">
                                    <label for="">VILLAGE</label>
                                    <select required  name="village_id" class="form-control cmp">
                                        <option>Selectionner un village  ...</option>
                                        @foreach($villages as $it)
                                            <option value="{{ $it->id }}">{{ $it->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mt-3">
                                    <label for="">LOCALISATION GPS</label>
                                    <input type="text" required class="form-control" name="localisation" >
                                </div>
                                <div class="mt-3">
                                    <label for="">PHOTO</label>
                                    <input type="file" name="photo" class="form-control">
                                </div>
                            </div>
                        <div class="mt-5 d-grid">
                            <button type="submit" class="btn-primary btn">ENREGISTRER</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
