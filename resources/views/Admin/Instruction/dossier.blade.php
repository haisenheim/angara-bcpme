@extends('Layouts.admin')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">ANGARA</a></li>
       <li class="breadcrumb-item"><a href="#">INSTRUCTION</a></li>
       <li class="breadcrumb-item active" aria-current="page">DOSSIER</li>
    </ol>
 </nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">INSTRUCTION DU DOSSIER</h5>
        <p class="lead">DEBUT D'INSTRUCTION D'UN DOSSIER</p>
    </div>
@endsection



@section('content')
    <div class="d-flex gap-1">
        <div class="card w-400px">
            <div class="card-body">
                <form enctype="multipart/form-data" id="form" method="post">
                    @csrf
                    <div class="form-group">
                        <input type="hidden" id="dossier_id" value="{{ $id }}" placeholder="Saisir ici l'ID assigne au dossier dans le service de creation" name="dossier_id" class="form-control">
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
                <h3>{{ $sme['name'] }}</h3>
                <h4>{{ $sme['mention'] }}</h4>
                <p class="lh-base">{{ $sme['description'] }}</p>
            </div>
        </div>
        <div class="card flex-fill">
            <div style="height: 70vh; overflow:scroll;" class="card-body">
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th>Critere Principaux</th>
                            <td>&numero; sous-critere</td>
                            <td>Pourcentage</td>
                            <th>Sous - critere</th>
                            <th>Valeur</th>
                            <th>Note par sous-critere</th>
                            <th>Note ponderee</th>
                            <th>Note critere pondere</th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th rowspan="{{ count($criteres[0]['souscriteres'])+1 }}">{{ $criteres[0]['name'] }}</th>
                        </tr>
                        @foreach($criteres[0]['souscriteres'] as $sc)
                            <tr>
                                <td>{{ $sc['sequence'] }}</td>
                                <td>{{ $sc['default'] }}%</td>
                                <td>{{ $sc['name'] }}</td>
                                <td>{{ isset($sc['reponses'][0])?$sc['reponses'][0]['choice']['valeur']:'-' }} <span class="float-right"><button data-bs-toggle="modal" data-bs-target="#critereModal" data-name="{{ $sc['name'] }}" data-dossier_id="{{ $id }}" data-id="{{ $sc['id'] }}" class="btn btn-xs btn-critere"><i class="pli-pencil"></i></button></span></td>
                                <td>{{ isset($sc['reponses'][0])?$sc['reponses'][0]['note']:'-' }}</td>
                                <td>{{ isset($sc['reponses'][0])?($sc['reponses'][0]['note']*$sc['default']/100):'-' }}</td>
                                <td></td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="7"></td>
                            <th>{{ $notes[0]['note'] }}</th>
                        </tr>
                        <tr>
                            <th rowspan="{{ count($criteres[1]['souscriteres'])+1 }}">{{ $criteres[1]['name'] }}</th>
                        </tr>
                        @foreach($criteres[1]['souscriteres'] as $sc)
                            <tr>
                                <td>{{ $sc['sequence'] }}</td>
                                <td>{{ $sc['default'] }}%</td>
                                <td>{{ $sc['name'] }}</td>
                                <td>{{ isset($sc['reponses'][0])?$sc['reponses'][0]['choice']['valeur']:'-' }} <span class="float-right"><button data-bs-toggle="modal" data-bs-target="#critereModal" data-name="{{ $sc['name'] }}" data-dossier_id="{{ $id }}" data-id="{{ $sc['id'] }}" class="btn btn-xs btn-critere"><i class="pli-pencil"></i></button></span></td>
                                <td>{{ isset($sc['reponses'][0])?$sc['reponses'][0]['note']:'-' }}</td>
                                <td>{{ isset($sc['reponses'][0])?($sc['reponses'][0]['note']*$sc['default']/100):'-' }}</td>
                                <td></td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="7"></td>
                            <th>{{ $notes[1]['note'] }}</th>
                        </tr>
                        <tr>
                            <td rowspan="{{ count($note3['details'])+1 }}">FINANCE</td>
                        </tr>
                            @foreach($note3['details'] as $sc)
                            <tr>
                                <td>{{ $sc['sequence'] }}</td>
                                <td>{{ $sc['pourcentage'] }}%</td>
                                <td>{{ $sc['critere'] }}</td>
                                <td>{{ $sc['valeur'] }}</td>
                                <td>{{ $sc['note'] }}</td>
                                <td>{{ $sc['pondere'] }}</td>
                                <td></td>
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan="7"></td>
                                <th >{{ $note3['note'] }}</th>
                            </tr>
                            <tr>
                                <th rowspan="{{ count($criteres[3]['souscriteres'])+1 }}">{{ $criteres[3]['name'] }}</th>
                            </tr>
                            @foreach($criteres[3]['souscriteres'] as $sc)
                                <tr>
                                    <td>{{ $sc['sequence'] }}</td>
                                    <td>{{ $sc['default'] }}%</td>
                                    <td>{{ $sc['name'] }}</td>
                                    <td>{{ isset($sc['reponses'][0])?$sc['reponses'][0]['choice']['valeur']:'-' }} <span class="float-right"><button data-bs-toggle="modal" data-bs-target="#critereModal" data-name="{{ $sc['name'] }}" data-dossier_id="{{ $id }}" data-id="{{ $sc['id'] }}" class="btn btn-xs btn-critere"><i class="pli-pencil"></i></button></span></td>
                                    <td>{{ isset($sc['reponses'][0])?$sc['reponses'][0]['note']:'-' }}</td>
                                    <td>{{ isset($sc['reponses'][0])?($sc['reponses'][0]['note']*$sc['default']/100):'-' }}</td>
                                    <td></td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="7"></td>
                                <th>{{ $notes[3]['note'] }}</th>
                            </tr>
                            <tr>
                                <th colspan="4"></th>
                                <th colspan="2">NOTE PONDERE FINALE/CRITERE</th>
                                <th colspan="2">NOTATION PME</th>
                            </tr>
                            <tr>
                                <th colspan="4"></th>
                                <th colspan="2">{{ $nf }}</th>
                                <th colspan="2">{{ $sme['name'] }}</th>
                            </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="critereModal">
        <div class="modal-dialog modal-dialog-scrollable modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header justify-content-between">
                    <h5 class="modal-title">Choix de la valeur</h5>
                    <div style="float: right">
                        <button data-bs-dismiss="modal" class="btn btn-sm" >x</button>
                    </div>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.instruction.critere.reponse') }}" method="post">
                        @csrf
                        <input type="hidden" id="_dossier_id" name="dossier_id">
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
            var url = "{{ route('admin.instruction.critere.choices') }}"
            var name = $(this).data('name')
            var dossier_id = $(this).data('dossier_id')
            var id = $(this).data('id')
            $('#name').text(name)
            $('#_dossier_id').val(dossier_id)
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
                    //window.location.replace('/admin/instruction/dossier/'+dossier_id)
                },
                //processData: false,
               // contentType: false
            } );
        })
        //var url = "{{ route('admin.instruction.dsf') }}"

        $( '#form' )
        .submit( function( e ) {
            var _url = "http://localhost:8080/dossier"
            var dossier_id = $('#dossier_id').val()
            console.log(dossier_id)
            $.ajax( {
            url: _url,
            type: 'POST',
            data: new FormData( this ),
            success:function(data){
                console.log(data)
                window.location.replace('/admin/instruction/dossier/'+dossier_id)
            },
            processData: false,
            contentType: false
            } );
            e.preventDefault();
        } );
    </script>

@endsection
