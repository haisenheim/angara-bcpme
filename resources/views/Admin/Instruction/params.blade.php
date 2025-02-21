@extends('Layouts.admin')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">Angara</a></li>
       <li class="breadcrumb-item"><a href="#">Parametres</a></li>
       <li class="breadcrumb-item active" aria-current="page">Criteres</li>
    </ol>
 </nav>
@endsection

@section('actions')
    <a href="#" data-bs-target="#addModal" data-bs-toggle="modal" class="btn btn-primary btn-sm"><i class="demo-pli-add me-2 fs-5"></i> Ajouter</a>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Parametrage des criteres</h5>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <input id="programme_id" class="form-control" placeholder="Saisir l'id du programme ici ... (Juste pour le text)" type="number">
        </div>
        <div class="card-body">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Critere Principaux</th>
                        <td>&numero; sous-critere</td>
                        <th>Sous - critere</th>
                        <th>Ponderation</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td rowspan="{{ count($item['souscriteres'])+1 }}">{{ $item['name'] }}</td>
                        </tr>
                            @foreach($item['souscriteres'] as $sc)
                            <tr>
                                <td>{{ $sc['sequence'] }}</td>
                                <td>{{ $sc['name'] }}</td>
                                <td data-critere_id="{{ $sc['id'] }}" class="td-edit border-1 border-muted" contenteditable="true">{{ $sc['default'] }}</td>
                            </tr>
                            @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            <button id="btn-save" class="btn-primary btn"><i class="pli-save"></i> Enregistrer</button>
        </div>
    </div>

    <script>
        $('#btn-save').click(function(){
            var ponderations = [];
            var programme_id= $('#programme_id').val()
            $('.td-edit').each(function(){
                var critere_id = $(this).data('critere_id')
                var ponderation = $(this).text()
                ponderations.push({
                    critere_id:critere_id,
                    ponderation:ponderation
                })

            })
            //console.log(ponderations)
            $.ajax({
                url:'http://localhost:8080/test',
                type:'post',
                dataType:'json',
                data:{ponderations:ponderations,programme_id:programme_id},
                success:function(data){
                    console.log(data)
                }
            })
        })
    </script>

    <style>
        .form-group{
            margin-top: 1rem;
        }
    </style>
@endsection
