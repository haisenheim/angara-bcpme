@extends('Layouts.cooperative')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">Angara</a></li>
       <li class="breadcrumb-item"><a href="#">Offres commerciales</a></li>
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
            <li><a data-bs-toggle="modal" data-bs-target="#addModal" class="dropdown-item" href="#">Declarer une part</a></li>   
        </ul>
    </div>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">OFFRE &numero; {{ $item->name }}</h5>
        <p class="lead">Détails de l'offre commerciale</p>
    </div>
@endsection

@section('content')
    <div class="d-flex flex-column justify-content-center">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <p class="mb-0">CLIENT: <span class="badge bg-primary">{{ $item->client->name }}</span></p>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <p>Date d'emission : <strong class="badge bg-primary">{{ $item->created_at->format('d/m/Y H:i') }}</strong></p>
                    <p>Date de livraison : <strong class="badge bg-primary">{{ \Carbon\Carbon::parse($item->day)->format('d/m/Y') }}</strong></p>
                </div>

                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th colspan="2"></th>
                            <th class="text-center" colspan="4">QUANTITES</th>
                            <th class="text-center" colspan="3">PRIX/KG</th>
                            <th class="text-center" colspan="4"></th>
                        </tr>
                        <tr>
                            <th>DATE DE RAMASSAGE</th>
                            <th>LIEU DE RAMASSAGE</th>
                            <th>GRADE 1</th>
                            <th>GRADE 2</th>
                            <th>HS</th>
                            <th>TOTAL</th>
                            <th>GRADE 1</th>
                            <th>GRADE 2</th>
                            <th>HS</th>
                            <th>TOTAL</th>
                            <th>VERSEMENT</th>
                            <th>RESTE</th>
                        </tr>
                    </thead>
                    <tbody>
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($item->day)->format('d/m/Y')  }}</td>
                                <td>{{ $item->lieu  }}</td>
                                <td>{{ $item->grd1_qty }} tonnes</td>
                                <td>{{ $item->grd2_qty }} tonnes</td>
                                <td>{{ $item->hs_qty }} tonnes</td>
                                <th>{{ $item->quantity }} tonnes</th>
                                <td>{{ $item->grd1_price }}</td>
                                <td>{{ $item->grd2_price }}</td>
                                <td>{{ $item->hs_price }}</td>
                                <th>{{ number_format($item->total,0,',','.') }} FCFA</th>
                                <th>{{ number_format($item->versement,0,',','.') }} FCFA</th>
                                <th>{{ number_format($item->reste,0,',','.') }} FCFA</th>
                                
                            </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <h6><i class="pli-money-2 fs-3"></i> Historique des paiements de cette vente</h6>
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th>DATE</th>
                            <th>DATE DE PAIEMENT</th>
                            <th>&numero; PAIEMENT</th>
                            <th>MONTANT</th>
                            <th>COMPTE</th>
                            <th>JUSTIFICATIF</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($item->paiements as $p)
                        <tr>
                            <td>{{ $p->created_at->format('d/m/Y H:i')  }}</td>
                            <td>{{ \Carbon\Carbon::parse($p->day)->format('d/m/Y')  }}</td>
                            <th>{{ $p->name }}</th>
                            <th>{{ number_format($p->montant,0,',','.') }} FCFA</th>
                            <td>{{ $p->compte }}</td>
                            <td><a href="{{ $p->justificatif }}">cliquer ici</a></td>
                            <td>
                                <a class="btn btn-xs btn-add" data-id="{{ $p->id }}" data-bs-toggle="modal" data-bs-target="#addPayModal" title="Enregistrer un paiement producteur" href="#"><i class="pli-coins fs-5 me-2"></i></a>
                            </td>
                           
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card mt-2">
            <div class="card-body">
                <h6 class="card-title border-bottom pb-2"> <i class="pli-pie-chart-3 fs-3"></i>  LISTE DES PARTS</h6>
                <table class="table table-sm table-bordered table-striped">
                    <thead>
                        <tr>
                            <th></th>
                            <th colspan="4">QUANTITES</th>
                            <th colspan="3"></th>
                        </tr>
                        <tr>
                            <th>BENEFICIAIRE</th>
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
                        @foreach($item->parts as $part)
                            <tr>
                                <td>{{ $part->exploitant->name }}</td>
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
                <h6 class="border-bottom pb-1">Details des versements aux producteurs</h6>
                <table class="table table-sm table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>DATE</th>
                            <th>PAIEMENT</th>
                            <th>EXPLOITANT</th>
                            <th>MONTANT</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($item->pps as $pp)
                            <tr>
                                <td>{{ $pp->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $pp->paiement->name }}</td>
                                <td>{{ $pp->exploitant->name }}</td>
                                <th>{{ number_format($pp->montant,0,',','.') }} FCFA</th>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header justify-content-between">
                    <h5 class="modal-title">Nouvelle part</h5>
                    <div style="float: right">
                        <button data-bs-dismiss="modal" class="btn btn-sm" >x</button>
                    </div>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" action="{{ route('cooperative.invoice.part.add') }}" method="post">
                        @csrf
                        <input type="hidden" id="id" name="item_id" value="{{ $item->id }}">
                        <div>
                            <div>
                                <label for="">BENEFICIAIRE</label>
                                <select required name="exploitant_id" id="" class="form-control">
                                    <option value="">Selectionner un adhérent ...</option>
                                    @foreach($members as $mbr)
                                        <option value="{{ $mbr->id }}">{{ $mbr->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <fieldset class="mt-2">
                            <legend>Quantités en kilo (kg)</legend>
                            <div class="d-flex gap-2 ">
                                <div class="flex-fill">
                                    <label for="">GRADE 1</label>
                                    <input required type="number" placeholder="Quantite de grade 1 fournie en KG" name="grd1_qty" value="0" class="form-control">
                                </div>
                                <div class="flex-fill">
                                    <label for="">GRADE 2</label>
                                    <input required type="number" name="grd2_qty" placeholder="Quantite de grade 2 fournie en KG" value="0" class="form-control">
                                </div>
                                <div class="flex-fill">
                                    <label for="">HS</label>
                                    <input required type="number" name="hs_qty" placeholder="Quantite de HS fournie en KG" value="0" class="form-control">
                                </div>
                            </div>
                        </fieldset>
                        <div class="mt-2">
                            <button type="submit" class="btn-primary btn">ENREGISTRER</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addPayModal">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header justify-content-between">
                    <h5 class="modal-title">Nouveau paiement de producteur</h5>
                    <div style="float: right">
                        <button data-bs-dismiss="modal" class="btn btn-sm" >x</button>
                    </div>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" action="{{ route('cooperative.invoice.part.paiement') }}" method="post">
                        @csrf
                        <input type="hidden" id="paiement_id" name="paiement_id">
                        <div class="d-flex gap-2">
                            <div class="flex-fill">
                                <label for="">BENEFICIAIRE</label>
                                <select required name="part_id" id="" class="form-control">
                                    <option value="">Selectionner un adhérent ...</option>
                                    @foreach($item->parts as $part)
                                        <option value="{{ $part->id }}">{{ $part->exploitant->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-25">
                                <label for="">MONTANT EN FCFA</label>
                                <input type="number" name="montant" value="0" class="form-control">
                            </div>
                        </div>
                        <div class="mt-2">
                            <button type="submit" class="btn-primary btn">ENREGISTRER</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            $('.btn-add').click(function(){
                var id = $(this).data('id');
                $('#paiement_id').val(id);
            })

        })
    </script>

 
@endsection
