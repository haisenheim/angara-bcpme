@extends('Layouts.admin')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">ANGARA</a></li>
       <li class="breadcrumb-item"><a href="#">entreprises</a></li>
       <li class="breadcrumb-item active" aria-current="page">{{ $item['name'] }}</li>
    </ol>
 </nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">{{ $item['name'] }}</h5>
        <p class="lead">Details sur l'entreprise</p>
    </div>
@endsection



@section('content')
    <div class="d-flex gap-1">
        <div class="card w-25">
            <div class="card-body">
                <h5>entreprise : {{ $item['name'] }}</h5>
            </div>
        </div>
        <div class="card w-75">
            <div class="card-body">
                <h5>Liste des soumissions</h5>
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>DOSSIER</th>
                            <th>PROGRAMME</th>
                            <th>NOTE</th>
                            <th>ETAT</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($item['dossiers'] as $dossier)
                            <tr>
                                <td>{{ $dossier['name'] }}</td>
                                <td>{{ $dossier['programme']['name'] }}</td>
                                <td>{{ $dossier['note'] }}</td>
                                <td> <span class="badge bg-{{ $dossier['state']['status']?'success':'warning' }}">{{ $dossier['state']['name'] }} </span></td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-xs btn-outline-primary dropdown-toggle hstack gap-2" data-bs-toggle="dropdown" aria-expanded="false">
                                           Actions
                                           <span class="vr"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            @if($dossier['state']['status'])
                                                <li><a class="dropdown-item" href="{{ route('admin.dossier.show',$dossier['id']) }}">Afficher</a></li>
                                            @else
                                                <li><a class="dropdown-item" href="{{ route('admin.dossier.instruction.create',$dossier['id']) }}">Demarrer l'instruction</a></li>
                                            @endif

                                        </ul>
                                     </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div style="height: 300px; overflow:scroll" class="mt-2">
        <div class="card">
            <div class="card-body">
                <h4>ETAT DES ENGAGEMENTS</h4>
                <table class="table sm table-bordered">
                    <thead>
                        <tr>
                            <th colspan="1"></th>
                            <th colspan="4">ENCOURS</th>
                            <th colspan="4">SOLLICITES</th>
                            <th colspan="3">TOTAL</th>
                        </tr>
                        <tr>
                            <th>ENGAGEMENT</th>
                            <th>MONTANT</th>
                            <th>PART</th>
                            <th>IMPAYES</th>
                            <th>DATE DE VALIDITE</th>

                            <th>MONTANT</th>
                            <th>PART</th>
                            <th>DATE DE VALIDITE</th>
                            <th>VARIATION</th>

                            <th>MONTANT</th>
                            <th>PART</th>
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


    <div class="modal fade" id="editModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header justify-content-between">
                    <h5 class="modal-title">Editer</h5>
                    <div style="float: right">
                        <button data-bs-dismiss="modal" class="btn btn-sm" >x</button>
                    </div>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" action="{{ route('admin.entreprise.set.engagement') }}" method="post">
                        <input type="hidden" name="engagement_id" id="engagement_id">
                        <input type="hidden" name="entreprise_id" value="{{ $item['id'] }}">
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
                                        <div class="mt-1">
                                            <label for="">Montant</label>
                                            <input required value="0" type="number" class="form-control" name="encours_montant">
                                        </div>
                                        <div class="mt-1">
                                            <label for="">Impayé</label>
                                            <input required value="0" type="number" class="form-control" name="encours_impaye">
                                        </div>
                                        <div class="mt-1">
                                            <label for="">Date de validité</label>
                                            <input required value="0" type="date" class="form-control" name="encours_dt_validite">
                                        </div>
                                    </fieldset>
                                    <fieldset>
                                        <legend>Enagagements en sollicités</legend>
                                        <div class="mt-1">
                                            <label for="">Montant</label>
                                            <input required value="0" type="number" class="form-control" name="sollicite_montant">
                                        </div>
                                        <div class="mt-1">
                                            <label for="">Date de validité</label>
                                            <input required value="0" type="date" class="form-control" name="sollicite_dt_validite">
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



@endsection
