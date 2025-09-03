@extends('../Layouts.tenant.agent')

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

    </ul>
</div>
@endsection

@section('content')
    <div>
        <div style="" class="row">
            <div class="col-md-12 col-sm-12">
                <div style="max-height: 900px; overflow: scroll;" class="card">
                    <div style="" class="card-body">
                        <div class="">
                            <!-- Underline nav tabs with base -->
                            <div class="tab-base">
                            <!-- Nav tabs -->
                            <h3>{{ $entreprise->name }}</h3>
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
                                                <th>&numero; sous-critère</th>
                                                <th>Pourcentage</th>
                                                <th>Sous - critère</th>
                                                <th>Valeur</th>
                                                <th>Note par sous-critere</th>
                                                <th>Note pondérée</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($categories as $category )
                                                @if($category->criteres->count())
                                                    <tr>
                                                        <th class="vertical-align" rowspan="{{ count($category->criteres)+1 }}">{{ $category['name'] }}</th>
                                                    </tr>
                                                    @foreach($category->criteres as $sc)
                                                        <tr class="border">
                                                            <td class="border">{{ $sc->id }}</td>
                                                            <td class="border">{{ $sc->poids }}%</td>
                                                            <td>{{ $sc['name'] }}</td>
                                                            <?php $answer = $answers->where('critere_id',$sc->id)->first() ?>
                                                            <td>{{ $answer?->choice?->name }} <span><button data-id="{{ $sc['id'] }}" data-bs-target="#critereModal" data-bs-toggle="modal" class="btn btn-xs btn-critere"><i class="pli-pencil"></i></button></span></td>
                                                            <td>{{ $answer?->choice?->valeur }}</td>
                                                            <td>{{ $answer?->choice?->valeur * $sc->poids/100 }}</td>

                                                        </tr>
                                                    @endforeach
                                                    <tr class="bg-light">
                                                        <td colspan="4"></td>
                                                        <th colspan="2">Note critère pondérée</th>
                                                        <th colspan="1">{{ isset($notes[$category->id])?$notes[$category->id]['pondere']:0 }}</th>
                                                    </tr>
                                                @endif
                                            @endforeach
                                                <tr class="border border-dark">
                                                    <th colspan="3"></th>
                                                    <th colspan="2">NOTE PONDERE FINALE/CRITERE</th>
                                                    <th colspan="2">NOTATION SME</th>
                                                </tr>
                                                <tr style="position: sticky; top: 30px" class="border border-dark">
                                                    <th colspan="4"></th>
                                                    <th class="fw-bold" colspan="2"></th>
                                                    <th class="fw-bold" colspan="2"></th>
                                                </tr>
                                        </tbody>
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
                <form action="{{ route('scoring.critere.reponse') }}" method="post">
                    @csrf
                    <input type="hidden" name="entreprise_id" value="{{ $entreprise->id }}">
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
    var url = "{{ route('scoring.critere.choices') }}"
    var id = $(this).data('id')
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
                $('#critere_id').append(`<option value=${choice.id}>${choice.name}</option>`)
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
