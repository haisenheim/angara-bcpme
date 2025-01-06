<?php

namespace App\Http\Controllers\Ca;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProgrammeListResource;
use App\Models\Banque;
use App\Models\Indicateur;
use App\Models\Organisme;
use App\Models\Programme;
use Illuminate\Support\Facades\Session;

class ProgrammeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('/Ca/Programmes/index');
    }


    public function fetchAll(){
        $items = Programme::all();
        $items = ProgrammeListResource::collection($items);
        return response()->json($items);
    }

    public function show(string $token)
    {
        //
        $item = Programme::where('token',$token)->first();
        if(!$item){
            Session::flash('error','Accès non autorisé à ce programme!');
            return back();
        }
        $banques = Banque::all();
        $organismes = Organisme::all();
        $indicateurs = Indicateur::all();
        return view('Ca/Programmes/show',compact('item','banques','organismes','indicateurs'));
    }


}
