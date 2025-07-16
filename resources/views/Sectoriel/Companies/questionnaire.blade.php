@extends('Layouts.sectoriel')

@section('title', 'Accueil')
@section('breadcrumb')
<link rel="stylesheet" href="{{ asset('dropdowncombotree/comboTreeStyle.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('jquery-easyui/themes/default/easyui.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('jquery-easyui/themes/icon.css') }}">
<script type="text/javascript" src="{{ asset('jquery-easyui/jquery.easyui.min.js') }}"></script>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">Angara</a></li>
       <li class="breadcrumb-item"><a href="#">Entreprises</a></li>
       <li class="breadcrumb-item active" aria-current="page">Mise en relation</li>
    </ol>
 </nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Formulaire de mise en relation</h5>
    </div>
@endsection

@section('content')
    <div class="d-flex justify-content-center">
        <div class="d-flex gap-0" style="width: 900px">
            <div style="width: 20px;" class="bg-blue">

            </div>
            <div class="card flex-fill">
                <div class="card-body">
                    <div id="my-form">
                        @csrf
                        <input type="hidden" id="id" name="id" value="{{ $item->id }}">
                        <input type="hidden" id="token" name="token" value="{{ $item->token }}">
                        <div>
                            <div class="tab-base tab-vertical d-flex">
                                <!-- Nav tabs -->
                                <ul class="nav nav-tabs w-25" style="height: 60vh; overflow: scroll;" role="tablist">
                                    @foreach ($criteres as $sc)
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link {{ $sc->id==1?'active':'' }}" data-bs-toggle="tab" data-bs-target="#_vtab_{{ $sc->id }}" type="button" role="tab" aria-controls="tab_{{ $sc->id }}" aria-selected="true">{{ $sc->name }}</button>
                                     </li>
                                    @endforeach
                                </ul>
                                <!-- Tabs content -->
                                <div class="tab-content flex-fill">
                                    @foreach ($criteres as $sc)
                                    <div id="_vtab_{{ $sc->id }}" class="tab-pane fade {{ $sc->id==1?'show active':'' }}" role="tabpanel" aria-labelledby="v{{ $sc->id }}-tab">
                                        <h5>{{ $sc->name }}</h5>
                                        <table class="table table-sm table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Question</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($sc->questions as $question)
                                                    <tr>
                                                        <td>{{ $question->name }}</td>
                                                        <td>
                                                            <select data-critere_id="{{ $sc->critere_id }}" data-sc_id="{{ $sc->id }}" data-question_id="{{ $question->id }}" name="" id="" class="form-control choice">
                                                                <option value=0>Choisir ...</option>
                                                                @foreach ($question->choices as $choice)
                                                                    <option data-value="{{ $choice->value }}" value="{{ $choice->id }}">{{ $choice->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                     </div>
                                    @endforeach
                                </div>

                             </div>
                            </div>
                        </div>
                </div>
                <div class="card-footer">
                    <button id="btn-save" class="btn btn-primary">Enregister</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        var _url = "{{ route('sectoriel.entreprise.questionnaire.save') }}"
        var token = $('#token').val();
        $(document).ready(function(){
            $('#btn-save').click(function(){
                var resps = [];
                var id = $("#id").val()
                $('.choice').each(function(){
                    var val = $(this).find('option:selected').data('value');
                    var elt = {
                        critere_id:$(this).data('critere_id'),
                        sous_critere_id:$(this).data('sc_id'),
                        question_id:$(this).data('question_id'),
                        choice_id:$(this).val(),
                        entreprise_id:id,
                        value:val,
                    }
                    if(elt.value!=0){
                        resps.push(elt)
                    }
                })
                var _token = $("input[name='_token']").val()
                $.ajax({
                    url:_url,
                    type:'post',
                    dataType:'json',
                    data:{choices:resps,_token:_token},
                    success:function(data){
                        //console.log(data)
                        window.location.replace('/sectoriel/entreprises/'+token)
                    },
                    error:function(err){
                        console.error(err)
                    }
                })
                console.log(resps)
            })
        })
    </script>

@endsection
