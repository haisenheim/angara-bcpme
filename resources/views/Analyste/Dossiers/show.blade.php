@extends('Layouts.analyste')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">ANGARA</a></li>
       <li class="breadcrumb-item"><a href="#">DOSSIER INSTRUCTION</a></li>
       <li class="breadcrumb-item active" aria-current="page">{{ $item['name'] }}</li>
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
        <li><a class="dropdown-item" href="{{ route('analyste.entreprise.get.engagements',$item->entreprise->token) }}">Etat des engagement de l'entreprise</a></li>
        <li><a class="dropdown-item" href="{{ route('analyste.dossier.get.grille.analyse',$item->token) }}">Grille d'analyse critique</a></li>
    </ul>
</div>
@endsection

@section('content')
    <div>
        <div style="" class="row">
            <div class="col-md-3 col-sm-12">
                <div style="max-height: 600px; overflow: scroll;" class="card">
                    <div class="card-header">
                        <p class="lh-base"><span class="label">ENTREPRISE : </span> <span>{{ $item->entreprise?->name }}</span></p>
                        <p class="lh-base"><span class="label">PROGRAMME : </span> <span>{{ $item->programme?->name }}</span></p>
                    </div>
                    <div class="card-body">
                        <form enctype="multipart/form-data" action="{{ route('analyste.dossier.dsf') }}" id="form" method="post">
                            @csrf
                            <div class="form-group">
                                <input type="hidden" id="dossier_id" value="{{ $item->id }}" name="dossier_id" class="form-control">
                                <input type="hidden" id="token" value="{{ $item->token }}">
                            </div>
                            <div class="form-group">
                                <label for="">ANNEE N</label>
                                <input type="number" name="annee" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">FICHIER DSF</label>
                                <input type="file" name="upload" class="form-control">
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary"><i class="pli-save"></i>ENREGISTRER</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer">
                        @if($sme)
                            <h3>{{ $sme['name'] }}</h3>
                            <h4>{{ $sme['mention'] }}</h4>
                            <p class="lh-base">{{ $sme['description'] }}</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-9 col-sm-12">
                <div style="max-height: 600px; overflow: scroll;" class="card">
                    <div style="" class="card-body">
                        <div class="">
                            <!-- Underline nav tabs with base -->
                            <div class="tab-base">
                            <!-- Nav tabs -->
                            <ul style="position: sticky; top:0;" class="nav nav-underline nav-component border-bottom" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link px-3 active" data-bs-toggle="tab" data-bs-target="#_dm-coTabsBaseHome" type="button" role="tab" aria-controls="home" aria-selected="true">GRILLE DE NOTATION</button>
                                </li>
                            </ul>


                            <!-- Tabs content -->
                            <div class="tab-content">
                                <div id="_dm-coTabsBaseHome" class="tab-pane fade active show" role="tabpanel" aria-labelledby="home-tab">

                                    <table class="table table-sm table-bordered table-notation">
                                        <thead>
                                            <tr>
                                                <th>Critère Principaux</th>
                                                <td>&numero; sous-critère</td>
                                                <td>Pourcentage</td>
                                                <th>Sous - critère</th>
                                                <th>Valeur</th>
                                                <th>Note par sous-critere</th>
                                                <th>Note pondérée</th>

                                            </tr>
                                        </thead>
                                        @if(count($indicateurs))
                                        <tbody>
                                            <tr>
                                                <th class="vertical-align" rowspan="{{ count($criteres[0]['souscriteres'])+1 }}">{{ $criteres[0]['name'] }}</th>
                                            </tr>
                                            @foreach($criteres[0]['souscriteres'] as $sc)
                                                <tr class="border">
                                                    <td class="border">{{ $sc['sequence'] }}</td>
                                                    <td class="border">{{ $sc['default'] }}%</td>
                                                    <td>{{ $sc['name'] }}</td>

                                                    <td>{{ isset($sc['reponse']['choice'])?$sc['reponse']['choice']['valeur']:'-' }} <span class="float-right"><button data-bs-toggle="modal" data-bs-target="#critereModal" data-name="{{ $sc['name'] }}" data-dossier_id="{{ $item->id }}" data-programme_id="{{ $item->programme_id }}" data-id="{{ $sc['id'] }}" class="btn btn-xs btn-critere"><i class="pli-pencil"></i></button></span></td>
                                                    <td>{{ isset($sc['reponse']['choice'])?$sc['reponse']['note']:'-' }}</td>
                                                    <td>{{ isset($sc['reponse']['choice'])?($sc['reponse']['note']*$sc['default']/100):'-' }}</td>

                                                </tr>
                                            @endforeach
                                            <tr class="bg-light">
                                                <td colspan="4"></td>
                                                <th colspan="2">Note critère pondérée</th>
                                                <th>{{ isset($criteres[0]['note'])?$criteres[0]['note']:0 }}</th>
                                            </tr>
                                            <tr>
                                                <th class="vertical-align" rowspan="{{ count($criteres[1]['souscriteres'])+1 }}">{{ $criteres[1]['name'] }}</th>
                                            </tr>
                                            @foreach($criteres[1]['souscriteres'] as $sc)
                                                <tr>
                                                    <td>{{ $sc['sequence'] }}</td>
                                                    <td>{{ $sc['default'] }}%</td>
                                                    <td>{{ $sc['name'] }}</td>
                                                    <td>{{ isset($sc['reponse']['choice'])?$sc['reponse']['choice']['valeur']:'-' }} <span class="float-right"><button data-bs-toggle="modal" data-bs-target="#critereModal" data-name="{{ $sc['name'] }}" data-dossier_id="{{ $item->id }}" data-programme_id="{{ $item->programme_id }}" data-id="{{ $sc['id'] }}" class="btn btn-xs btn-critere"><i class="pli-pencil"></i></button></span></td>
                                                    <td>{{ isset($sc['reponse']['choice'])?$sc['reponse']['note']:'-' }}</td>
                                                    <td>{{ isset($sc['reponse']['choice'])?($sc['reponse']['note']*$sc['default']/100):'-' }}</td>

                                                </tr>
                                            @endforeach
                                            <tr class="bg-light">
                                                <td colspan="4"></td>
                                                <th colspan="2">Note critère pondérée</th>
                                                <th>{{ isset($criteres[1]['note'])?$criteres[1]['note']:0  }}</th>
                                            </tr>
                                            <tr>
                                                <th class="vertical-align" rowspan="{{ count($indicateurs[0]['notation']['details'])+1 }}">FINANCE</th>
                                            </tr>
                                                @foreach($indicateurs[0]['notation']['details'] as $sc)
                                                <tr>
                                                    <td>{{ $sc['sequence'] }}</td>
                                                    <td>{{ $sc['pourcentage'] }}%</td>
                                                    <td>{{ $sc['critere'] }}</td>
                                                    <td>{{ $sc['valeur'] }}</td>
                                                    <td>{{ $sc['note'] }}</td>
                                                    <td>{{ $sc['pondere'] }}</td>

                                                </tr>
                                                @endforeach
                                                <tr class="bg-light">
                                                    <td colspan="4"></td>
                                                    <th colspan="2">Note critère pondérée</th>
                                                    <th >{{ $indicateurs[0]['notation']['note'] }}</th>
                                                </tr>
                                                <tr>
                                                    <th class="vertical-align" rowspan="{{ count($criteres[3]['souscriteres'])+1 }}">{{ $criteres[3]['name'] }}</th>
                                                </tr>
                                                @foreach($criteres[3]['souscriteres'] as $sc)
                                                    <tr>
                                                        <td>{{ $sc['sequence'] }}</td>
                                                        <td>{{ $sc['default'] }}%</td>
                                                        <td>{{ $sc['name'] }}</td>
                                                        <td>{{ isset($sc['reponse']['choice'])?$sc['reponse']['choice']['valeur']:'-' }} <span class="float-right"><button data-bs-toggle="modal" data-bs-target="#critereModal" data-name="{{ $sc['name'] }}" data-dossier_id="{{ $item->id }}" data-programme_id="{{ $item->programme_id }}" data-id="{{ $sc['id'] }}" class="btn btn-xs btn-critere"><i class="pli-pencil"></i></button></span></td>
                                                        <td>{{ isset($sc['reponse']['choice'])?$sc['reponse']['note']:'-' }}</td>
                                                        <td>{{ isset($sc['reponse']['choice'])?($sc['reponse']['note']*$sc['default']/100):'-' }}</td>

                                                    </tr>
                                                @endforeach
                                                <tr class="bg-light">
                                                    <td colspan="4"></td>
                                                    <th colspan="2">Note critère pondérée</th>
                                                    <th>{{ isset($criteres[3]['note'])?$criteres[3]['note']:0  }}</th>
                                                </tr>
                                                <tr class="border border-dark">
                                                    <th colspan="3"></th>
                                                    <th colspan="2">NOTE PONDERE FINALE/CRITERE</th>
                                                    <th colspan="2">NOTATION PME</th>
                                                </tr>
                                                <tr style="position: sticky; top: 30px" class="border border-dark">
                                                    <th colspan="4"></th>
                                                    <th class="fw-bold" colspan="2">{{ $item['note'] }}</th>
                                                    <th class="fw-bold" colspan="2">{{ $sme['name'] }}</th>
                                                </tr>
                                        </tbody>
                                        @endif
                                    </table>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="report1Modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header justify-content-between">
                    <div style="float: right">
                        <button data-bs-dismiss="modal" class="btn btn-sm" >x</button>
                    </div>
                </div>
                <div class="modal-body">
                    <p id="description"></p>
                    <form action="{{ route('analyste.dossier.set.analyse') }}" method="post">
                        @csrf
                        <input type="hidden" value="{{ $item['id'] }}" name="dossier_id">
                        <input type="hidden" id="sequence" name="sequence">
                        <div class="mt-2">
                            <x-quill :name="'content'"></x-quill>
                        </div>
                        <div class="mt-1">
                            <button type="submit" class="btn-primary btn">ENREGISTRER</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>








@endsection

@section('modal')

<div class="modal fade" id="critereModal">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header justify-content-between">
                <h5 class="modal-title">Choix de la valeur</h5>
                <div style="float: right">
                    <button data-bs-dismiss="modal" class="btn btn-sm" >x</button>
                </div>
            </div>
            <div class="modal-body">
                <form action="{{ route('analyste.instruction.critere.reponse') }}" method="post">
                    @csrf
                    <input type="hidden" id="_dossier_id" name="dossier_id">
                    <input type="hidden" id="_programme_id" name="programme_id">
                    <input type="hidden" id="id" name="critere_id">
                    <div class="mt-2">
                        <label id="name" for=""></label>
                        <select required name="choice_id" id="critere_id" class="form-control">

                        </select>
                    </div>
                    <div class="mt-1">
                        <button type="submit" class="btn-primary btn">ENREGISTRER</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $('.btn-critere').click(function(){
    var url = "{{ route('analyste.instruction.critere.choices') }}"
    var name = $(this).data('name')
    var dossier_id = $(this).data('dossier_id')
    var prg_id = $(this).data('programme_id')
    console.log(prg_id)
    var id = $(this).data('id')
    $('#name').text(name)
    $('#_dossier_id').val(dossier_id)
    $('#_programme_id').val(prg_id)
    $('#id').val(id)

    $.ajax({
        url: url,
        type: 'get',
        dataType:'json',
        data: {id:id},
        success:function(data){
            console.log(data)
            $('#critere_id').html('')
            $('#critere_id').append(`<option value="">Choisir ...</option>`)
            data.forEach(choice => {
                $('#critere_id').append(`<option value=${choice.id}>${choice.valeur}</option>`)
            });
            //window.location.replace('/analyste/instruction/dossier/'+dossier_id)
        },
        //processData: false,
       // contentType: false
    } );
})
</script>

<style>
    .table-notation th{
        border: var(--bs-border-width) var(--bs-border-style) #555 !important;
        font-weight: 900;
    }

    .table-notation td{
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
