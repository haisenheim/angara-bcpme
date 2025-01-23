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
        <h5 class="page-title mb-0 mt-2">INSTRCTION DU DOSSIER</h5>
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
                        <input type="hidden" id="dossier_id" value="{{ $id }}" name="dossier_id" class="form-control">
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

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        var url = "{{ route('admin.instruction.dsf') }}"
        var _url = "http://angara.pft-keka.com:8080/dossier"
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
                window.location.replace('/admin/dossier/'+dossier_id)
            },
            processData: false,
            contentType: false
            } );
            e.preventDefault();
        } );
    </script>

@endsection
