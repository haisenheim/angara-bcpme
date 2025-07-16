@extends('Layouts.analyste')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">ANGARA</a></li>
       <li class="breadcrumb-item"><a href="{{ route('analyste.entreprises.show',$entreprise->token) }}">{{ $entreprise->name }}</a></li>
       <li class="breadcrumb-item active" aria-current="page">Etat des engagements</li>
    </ol>
 </nav>
@endsection

@section('actions')
<div class="btn-group">
    <button type="button" class="btn btn-xs btn-outline-primary dropdown-toggle hstack gap-2" data-bs-toggle="dropdown" aria-expanded="false">
    Actions
    <span class="vr"></span>
    </button>
    <ul class="dropdown-menu analyse">
        <li><a data-sequence="1" class="dropdown-item" data-bs-target="#report1Modal" data-bs-toggle="modal" href="#">Brèves données générales actualisées sur l'emprunteur</a></li>
        <li><a data-sequence="2" class="dropdown-item" data-bs-target="#report1Modal" data-bs-toggle="modal" href="#">Analyse critique d'ensemble</a></li>

    </ul>
</div>
@endsection


@section('content')
    <div class="container">
        <div class="d-flex justify-content-center">
            <div style="max-width:1000px" class="card">
                <div class="card-header p-4">
                    <h4 class="text-center mb-0">ETAT DES ENGAGEMENTS</h4>
                </div>
                <div class="card-body table-responsive">
                    <table class="table sm table-bordered">
                        <thead>
                            <tr>
                                <th colspan="1"></th>
                                <th colspan="3">ENCOURS</th>
                                <th colspan="3">SOLLICITES</th>
                                <th colspan="2">TOTAL</th>
                            </tr>
                            <tr>
                                <th>ENGAGEMENT</th>
                                <th>MONTANT</th>
                                <th>IMPAYES</th>
                                <th>DATE DE VALIDITE</th>

                                <th>MONTANT</th>
                                <th>DATE DE VALIDITE</th>
                                <th>VARIATION</th>

                                <th>MONTANT</th>
                                <th>DATE DE VALIDITE</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($engagements as $eng)
                                <x-engagement :eng="json_encode($eng)"></x-engagement>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header justify-content-between">
                    <h5 class="modal-title">Editer</h5>
                    <div style="float: right">
                        <button data-bs-dismiss="modal" class="btn btn-sm" >x</button>
                    </div>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" action="{{ route('analyste.entreprise.set.engagement') }}" method="post">
                        <input type="hidden" name="engagement_id" id="engagement_id">
                        <input type="hidden" name="entreprise_id" value="{{ $entreprise['id'] }}">
                        @csrf
                            <div class="">
                                <div>
                                    <label for="">Banque</label>
                                    <select required name="banque_id" id="banque_id" class="form-control">
                                        <option value="">Selectionner la banque</option>
                                        @foreach ($banques as $item)
                                            <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mt-1">
                                    <fieldset>
                                        <legend>Enagagements en cours</legend>
                                        <div class="row">
                                            <div class="col-md-4 col-sm-12">
                                                <label for="">Montant</label>
                                                <input required value="0" type="number" class="form-control" name="encours_montant">
                                            </div>
                                            <div class="col-md-4 col-sm-12">
                                                <label for="">Impayé</label>
                                                <input required value="0" type="number" class="form-control" name="encours_impaye">
                                            </div>
                                            <div class="col-md-4 col-sm-12">
                                                <label for="">Date de validité</label>
                                                <input required value="0" type="date" class="form-control" name="encours_dt_validite">
                                            </div>
                                        </div>
                                    </fieldset>
                                    <fieldset>
                                        <legend>Enagagements en sollicités</legend>
                                        <div class="row">
                                            <div class="col-md-6 col-sm-12">
                                                <label for="">Montant</label>
                                                <input required value="0" type="number" class="form-control" name="sollicite_montant">
                                            </div>
                                            <div class="col-md-6 col-sm-12">
                                                <label for="">Date de validité</label>
                                                <input required value="0" type="date" class="form-control" name="sollicite_dt_validite">
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                        <div class="mt-5">
                            <button type="submit" class="btn-primary btn">ENREGISTRER</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $('.btn-edit').click(function(){
            var id = $(this).data('engagement_id')
            $('#engagement_id').val(id)
        })
    </script>

    <style>
        .table th{
            border: var(--bs-border-width) var(--bs-border-style) #555 !important;
            font-weight: 900;
        }

        .table td{
            border: var(--bs-border-width) var(--bs-border-style) #888 !important;
            font-weight: normal;
        }

        th.vertical-align{
            display: table-cell;
            vertical-align: middle;
            text-align: center;
        }
    </style>

@endsection


