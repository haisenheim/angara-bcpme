<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProgrammeListResource;
use App\Models\Banque;
use App\Models\Composante;
use App\Models\Indicateur;
use App\Models\Organisme;
use App\Models\Poste;
use App\Models\Programme;
use App\Models\ProgrammeAppui;
use App\Models\ProgrammeIndicateur;
use App\Models\ProgrammeOrgamisme;
use App\Models\ProgrammeProduit;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ProgrammeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('/Admin/Programmes/index');
    }


    public function fetchAll(){
        $items = Programme::all();
        $items = ProgrammeListResource::collection($items);
        return response()->json($items);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('/Admin/Programmes/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        //dd($request->all());
        $data = $request->except('_token','appuisnf','appuisf','produits','bailleurs','type_entreprise','type_personne');
        $anfs = explode(',',$request->appuisnf);
        $afs = explode(',',$request->appuisf);
        $produits = explode(',',$request->produits);
        $bailleurs = explode(',',$request->bailleurs);
        $data['token'] = sha1(time().rand(0,99));
        $data['user_id'] = auth()->user()->id;
        $data['type_pp'] = implode('-',$request->type_personne);
        $data['type_pm'] = implode('-',$request->type_entreprise);
        $item = Programme::create($data);

        foreach($afs as $a){
            ProgrammeAppui::create([
                'programme_id'=>$item->id,
                'service_id'=>$a
            ]);
        }
        foreach($anfs as $a){
            ProgrammeAppui::create([
                'programme_id'=>$item->id,
                'service_id'=>$a
            ]);
        }
        foreach($produits as $a){
            ProgrammeProduit::create([
                'programme_id'=>$item->id,
                'produit_id'=>$a
            ]);
        }

        foreach($bailleurs as $a){
            ProgrammeOrgamisme::create([
                'programme_id'=>$item->id,
                'organisme_id'=>$a
            ]);
        }
        Session::flash('success','Enregistrement effectué avec succès!');
        return redirect(route('admin.programmes.show',$item->token));

    }

    public function save(Request $request)
    {
        $data = $request->except('_token','type_entreprise','type_personne');
        $data['user_id'] = auth()->user()->id;
        $data['type_pp'] = implode('-',$request->type_personne);
        $data['type_pm'] = implode('-',$request->type_entreprise);
        $item = Programme::updateOrCreate(['id'=>$request->id],$data);
        Session::flash('success','Enregistrement effectué avec succès!');
        return redirect(route('admin.programmes.show',$item->token));

    }

    /**
     * Display the specified resource.
     */
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
        $services = Service::all();
        return view('Admin/Programmes/show',compact('item','banques','organismes','indicateurs','services'));
    }

    public function saveUser(Request $request){
         //dd($request->all());
         $user = new User();
         $user->name = $request->name;
         $user->email = $request->email;
         $user->phone = $request->phone;
         $user->password = bcrypt($request->password);
         $user->token = sha1(time().auth()->user()->id);
         $user->role_id = 19;
         $user->programme_id = $request->programme_id;
        $user->save();
        Session::flash('success','Enregistrement effectué avec succès!');
        return back();
     }

     public function savePoste(Request $request){
        // dd($request->all());
        Poste::create($request->all());
        Session::flash('success','Enregistrement effectué avec succès!');
        return back();
     }


    public function saveComposante(Request $request)
    {
        //dd($request->all());
        $token = $request->token;
        $data = $request->except('token');
        Composante::updateOrCreate(
            [
            'programme_id'=>$request->programme_id,
            'banque_id'=>$request->banque_id??0,
            'organisme_id'=>$request->organisme_id??0
            ],
            $data
        );
        Session::flash('success','Enregistrement effectué avec succès!');
        return redirect(route('admin.programmes.show',$token));
    }


    public function saveAppui(Request $request)
    {
        //dd($request->all());
        $token = $request->token;
        $data = $request->except('token');
        ProgrammeAppui::updateOrCreate(
            [
            'programme_id'=>$request->programme_id,
            'service_id'=>$request->service_id??0
            ],
            $data
        );
        Session::flash('success','Enregistrement effectué avec succès!');
        return redirect(route('admin.programmes.show',$token));
    }

    public function saveProduit(Request $request)
    {
        $token = $request->token;
        $data = $request->except('token');
        ProgrammeProduit::updateOrCreate(
            [
            'programme_id'=>$request->programme_id,
            'produit_id'=>$request->produit_id??0
            ],
            $data
        );
        Session::flash('success','Enregistrement effectué avec succès!');
        return redirect(route('admin.programmes.show',$token));
    }

    public function saveResultat(Request $request)
    {
        //dd($request->all());
        $token = $request->token;
        $data = $request->except('token');
        ProgrammeIndicateur::updateOrCreate(
            [
            'programme_id'=>$request->programme_id,
            'indicateur_id'=>$request->banque_id??0,
            ],
            $data
        );
        Session::flash('success','Enregistrement effectué avec succès!');
        return redirect(route('admin.programmes.show',$token));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $token)
    {
        //
        $item = Programme::where('token',$token)->first();
        return view('/Admin/Programmes/edit',compact('item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
