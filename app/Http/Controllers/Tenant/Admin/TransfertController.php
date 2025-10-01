<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\TransfertResource;
use App\Models\Structuration\Entrepot;
use App\Models\Structuration\EntrepotGamme;
use App\Models\Structuration\Gamme;
use App\Models\Structuration\Transfert;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TransfertController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        return view('Tenant/Admin/Transferts/index');
    }

    public function fetchAll(){
        $entrepots = Entrepot::where('tenant_id',tenant()->id)->get();
        $items = Transfert::whereIn('target_id',$entrepots->pluck('id'))->orWhereIn('source_id',$entrepots->pluck('id'))->orderBy('created_at','DESC')->get();
        $items = TransfertResource::collection($items);
        return response()->json($items);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $children = Tenant::where('union_id',tenant()->id)->get();
        $gammes = Gamme::where('domaine_id',tenant()->domaine_id)->get();
        $targets = Entrepot::where('tenant_id',tenant()->id)->get();
        return view('Tenant/Admin/Transferts/create',compact('gammes','targets','children'));
    }

    public function fetchSourceEntrepot($tenant_id){
        $sources = Entrepot::where('tenant_id',$tenant_id)->get();
        return response()->json($sources);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $source = Entrepot::find($request->source_id);
        $target = Entrepot::find($request->target_id);

        $sourceStock = EntrepotGamme::where('entrepot_id',$request->source_id)->where('gamme_id',$request->gamme_id)->where('tenant_id',tenant()->id)->where('saison_id',$this->_saison->id)->first();
        $targetStock = EntrepotGamme::where('entrepot_id',$request->target_id)->where('gamme_id',$request->gamme_id)->where('tenant_id',tenant()->id)->where('saison_id',$this->_saison->id)->first();
        if(!$sourceStock){
            $sourceStock = EntrepotGamme::create([
                'entrepot_id'=>$request->source_id,
                'gamme_id'=>$request->gamme_id,
                'tenant_id'=>tenant()->id,
                'saison_id'=>$this->_saison->id,
            ]);
        }
        if(!$targetStock){
            $targetStock = EntrepotGamme::create([
                'entrepot_id'=>$request->target_id,
                'gamme_id'=>$request->gamme_id,
                'tenant_id'=>tenant()->id,
                'saison_id'=>$this->_saison->id,
            ]);
        }
        if($sourceStock->quantity >= $request->quantity){
            $sourceStock->quantity = $sourceStock->quantity - $request->quantity;
            $sourceStock->save();
            $targetStock->quantity = $targetStock->quantity + $request->quantity;
            $targetStock->save();
            $data = $request->all();
            $source = Entrepot::find($request->source_id);
            $data['tenant_source_id'] = $source->tenant_id;
            $data['tenant_target_id'] = tenant()->id;
            $data['source_id'] = $request->source_id;
            $data['target_id'] = $request->target_id;
            $data['gamme_id'] = $request->gamme_id;
            $data['vehicule'] = $request->vehicule;
            $data['responsable'] = $request->responsable;
            $data['token'] = sha1(time());
            $data['user_id'] = auth()->user()->id;
            $data['day'] = $request->day;
            $data['quantity'] = $request->quantity;
            $data['saison_id'] = $this->_saison->id;
            Transfert::create($data);
        }else{
            Session::flash('error','Stock insuffisant!');
            return redirect()->route('admin.transferts.index');
        }


        Session::flash('success','Transfert enregistré avec succès!');
        return redirect()->route('admin.transferts.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Projet  $projet
     * @return \Illuminate\Http\Response
     */
	public function show($token)
	{
		$item = Entrepot::where('token',$token)->first();
		return view('/Tenant/Admin/Entrepots/show')->with(compact('item'));
	}


}
