@extends('../Layouts.tenant.admin')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">Angara</a></li>
       <li class="breadcrumb-item"><a href="#">EFFECTIFS</a></li>
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

        </ul>
    </div>
@endsection


@section('content')
    <div class="d-flex gap-2">
       <div class="w-300px">
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
         </div>
       </div>
       <div class="flex-fill">
            <div class="">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title border-bottom pb-2"> <i class="pli-pie-chart-3 fs-3"></i>  PRODUCTION DE LA SAISON</h6>
                        <table class="table table-sm table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th colspan="4">QUANTITES</th>
                                    <th colspan="3"></th>
                                </tr>
                                <tr>
                                    <th>GRADE 1</th>
                                    <th>GRADE 2</th>
                                    <th>HS</th>
                                    <th>TOTAL</th>
                                    <th>MONTANT</th>
                                    <th>VERSEMENT</th>
                                    <th>RESTE</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($parts as $part)
                                    <tr>
                                        <td> {{ $part->grd1_qty *1000 }} kg ({{ $part->grd1_qty }} t)</td>
                                        <td> {{ $part->grd2_qty *1000 }} kg ({{ $part->grd2_qty }} t)</td>
                                        <td> {{ $part->hs_qty *1000 }} kg ({{ $part->hs_qty }} t)</td>
                                        <th>{{ number_format($part->quantity*1000,0,',','.') }} kg ({{ $part->quantity }} t)</th>
                                        <th>{{ number_format($part->total,0,',','.') }} FCFA</th>
                                        <th>{{ number_format($part->versement,0,',','.') }} FCFA</th>
                                        <th>{{ number_format($part->reste,0,',','.') }} FCFA</th>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <h6 class="border-bottom pb-1">Details des versements</h6>
                        <table class="table table-sm table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>DATE</th>
                                    <th>PAIEMENT</th>
                                    <th>MONTANT</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pps as $pp)
                                    <tr>
                                        <td>{{ $pp->created_at->format('d/m/Y H:i') }}</td>
                                        <td>{{ $pp->paiement->name }}</td>
                                        <th>{{ number_format($pp->montant,0,',','.') }} FCFA</th>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
       </div>
    </div>

@endsection
