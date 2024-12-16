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

@section('actions')
    <div class="btn-group">
        <button type="button" class="btn btn-xs btn-outline-primary dropdown-toggle hstack gap-2" data-bs-toggle="dropdown" aria-expanded="false">
        Actions
        <span class="vr"></span>
        </button>
        <ul class="dropdown-menu">
            <li><a data-bs-toggle="modal" data-bs-target="#addModal" class="dropdown-item" href="#">Associer un libellé</a></li>
        </ul>
    </div>
@endsection

@section('content')
    <div class="d-flex gap-1">
        <div class="card w-400px">
            <div class="card-body">
                <form enctype="multipart/form-data" id="form" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="">ID DOSSIER</label>
                        <input type="number" id="dossier_id" value="{{ $id }}" placeholder="Saisir ici l'ID assigne au dossier dans le service de creation" name="dossier_id" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">ID PROGRAMME</label>
                        <input type="number" name="programme_id" class="form-control">
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
        </div>
        <div class="card flex-fill">
            <div class="card-body">
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
                            <td rowspan="{{ count($criteres[0]['souscriteres'])+1 }}">{{ $criteres[0]['name'] }}</td>
                        </tr>
                        @foreach($criteres[0]['souscriteres'] as $sc)
                            <tr>
                                <td>{{ $sc['sequence'] }}</td>
                                <td>{{ $sc['default'] }}%</td>
                                <td>
                                    <select name="" id="">
                                        @foreach($sc['choices'] as $choice)
                                            <option value="{{ $choice['id'] }}">{{ $choice['valeur'] }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>{{ $sc['name'] }}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="7"></td>
                            <th>xxxx</th>
                        </tr>
                        <tr>
                            <td rowspan="{{ count($criteres[1]['souscriteres'])+1 }}">{{ $criteres[1]['name'] }}</td>
                        </tr>
                        @foreach($criteres[1]['souscriteres'] as $sc)
                            <tr>
                                <td>{{ $sc['sequence'] }}</td>
                                <td>{{ $sc['default'] }}%</td>
                                <td>
                                    <select name="" id="">
                                        @foreach($sc['choices'] as $choice)
                                            <option value="{{ $choice['id'] }}">{{ $choice['valeur'] }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>{{ $sc['name'] }}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="7"></td>
                            <th>yyyy</th>
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
                                <td rowspan="{{ count($criteres[3]['souscriteres'])+1 }}">{{ $criteres[3]['name'] }}</td>
                            </tr>
                            @foreach($criteres[3]['souscriteres'] as $sc)
                                <tr>
                                    <td>{{ $sc['sequence'] }}</td>
                                    <td>{{ $sc['default'] }}%</td>
                                    <td>
                                        <select name="" id="">
                                            @foreach($sc['choices'] as $choice)
                                                <option value="{{ $choice['id'] }}">{{ $choice['valeur'] }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>{{ $sc['name'] }}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="7"></td>
                                <th>zzzz</th>
                            </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){

        })
        var url = "{{ route('admin.instruction.dsf') }}"
        var _url = "http://localhost:8080/dossier"
        var dossier_id = $('#dossier_id').val()
        console.log(dossier_id)
        $( '#form' )
        .submit( function( e ) {
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
