<?php

namespace App\Http\Controllers\Analyste;

use App\Http\Controllers\Controller;
use App\Models\Compte;
use App\Models\Libelle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class InstructionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $items = Libelle::all();
        return view('/Analyste/Libelles/index')->with(compact('items'));
    }

    public function getCritereParamsForm(){
        $criteres = Http::get('http://localhost:8080/test');
        $items = json_decode($criteres->body(),true);
        return view('/Analyste/Instruction/params')->with(compact('items'));
    }

    public function createDossier(){
        return view('/Analyste/Instruction/create');
    }

    public function loadDsf(){
        $upload = request('upload');
        $programme_id = request('programme_id');
        $dossier_id = request('dossier_id');
        $annee = request('annee');
        //dd($upload);
        //$upload->move('./dsf');
        $name_without_extension = time();
        $ext = $upload->getClientOriginalExtension();
        $name_with_extension = $name_without_extension . '.' . $ext;
            if(!file_exists(public_path('files'))){
                mkdir(public_path('files'));
            }
			if (file_exists(public_path('files') . '/' . $name_with_extension)) {
				unlink(public_path('files') . '/' . $name_with_extension);
			}
			$imageUri = 'files'.'/'.$name_with_extension;
			$upload->move(public_path('files'), $name_with_extension);
        $file =  [
                    'name' => 'upload',
                    'contents' => file_get_contents(public_path('files').'/'.$name_with_extension),
                ];

                //dd($file);
        $data = [
            'annee'=>$annee,
            'programme_id'=>$programme_id,
            'dossier_id'=>$dossier_id,
            'multipart'=>$file,
        ];
       $ret =  Http::
       //attach('upload', $upload)->
       post('http://localhost:8080/dossier',$data);
       dd($ret->body());
        return redirect(route('analyste.instruction.dossier',$dossier_id));
    }

    public function getDossier($id){
        $dossier = Http::get('http://localhost:8080/dossier/'.$id);
        $dossier = json_decode($dossier->body(),true);
        $note3 = $dossier['exercices'][0]['notation'];
       // dd($note3);
        return view('/Analyste/Instruction/dossier',compact('id','note3'));
    }

    public function findDossier(){
        $id = request('dossier_id');
        $programme_id = request('programme_id');
        $dossier = Http::get('http://localhost:8080/dossier?id='.$id.'&programme_id='.$programme_id);
        $dossier = json_decode($dossier->body(),true);
        $note3 = $dossier['exercices'][0]['notation'];
        $criteres = $dossier['criteres'];
        $notes = $dossier['notes'];
        $nf = $dossier['note'];
        $sme = $dossier['sme'];
       // dd($sme);
        return view('/Analyste/Instruction/dossier',compact('id','note3','criteres','notes','nf','sme'));
    }

    public function getChoices(){
        //$dossier_id = request('dossier_id');

        $id = request('id');
        $choices = Http::get('http://localhost:8080/critere/choices?id='.$id);
        $choices = json_decode($choices->body(),true);
       // dd($criteres);
        return response()->json($choices);
    }

    public function saveCritereReponse(){
        $choice_id = request('choice_id');
        $dossier_id = request('dossier_id');
        $critere_id = request('critere_id');
        $data = [
            'choice_id'=>$choice_id,
            'critere_id'=>$critere_id,
            'dossier_id'=>$dossier_id,
        ];
        $response = Http::post('http://localhost:8080/critere/reponse',$data);
        return back();
    }






    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        Libelle::create($data);
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Projet  $projet
     * @return \Illuminate\Http\Response
     */
	public function show($token)
	{
		//$projet = Creance::where('token',$token)->first();


		return view('/Consultant/Creances/show')->with(compact('projet'));
	}


}
