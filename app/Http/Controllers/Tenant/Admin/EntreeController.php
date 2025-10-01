<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Http\Controllers\ExtendedController;
use App\Http\Resources\EntreeResource;
use App\Jobs\ProcessPaymentJob;
use App\Models\Structuration\Agent;
use App\Models\Structuration\Caisse;
use App\Models\Structuration\Entree;
use App\Models\Structuration\Entrepot;
use App\Models\Structuration\EntrepotGamme;
use App\Models\Structuration\Gamme;
use App\Models\Structuration\Membre;
use App\Models\Structuration\Paiement;
use App\Models\Structuration\Wallet;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Services\PaymentService;

class EntreeController extends ExtendedController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('Tenant/Admin/Entrees/index');
    }

    public function fetchAll(){

        if(tenant()->is_union){
            $tenants = Tenant::where('union_id',tenant()->id)->get();
            $items = collect();
            foreach($tenants as $tenant){
                $items = $items->merge($this->fetchAllByTenant($tenant->id));
            }
            $items = $items->merge($this->fetchAllByTenant(tenant()->id));
            return response()->json($items);
        }else{
            $items = Entree::orderBy('created_at','DESC')->get();
            $items = EntreeResource::collection($items);
            return response()->json($items);
        }
    }

    public function fetchAllByTenant($tenant_id){
        $tenant = Tenant::find($tenant_id);
       $items = $tenant->run(function(){
            return Entree::orderBy('created_at','DESC')->get();
        });
        if($tenant_id!=tenant()->id){
            $items = $items->map(function($item)use($tenant){
                $item->tenant = $tenant;
                $item->tenant_id = $tenant->id;
                return $item;
            });
        }
        $items = EntreeResource::collection($items);

        return $items;
    }




    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

        $coop = tenant();
        $agents = User::where('role_id',2)->get();
        $entrepots = Entrepot::where('tenant_id',$coop->id)->get();
        $gammes = Gamme::where('domaine_id',$coop->domaine_id)->get();
        $exploitants = Membre::where('tenant_id',$coop->id)->get();
        $children = Tenant::where('union_id',$coop->id)->get();
        return view('Tenant/Admin.Entrees.create',compact('agents','entrepots','gammes','exploitants','children'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(tenant()->is_union){
        $tenant = Tenant::find($request->tenant_id);
       // dd($request->all());

        $tenant->run(function()use($request){
            $data = $request->except('tenant_id');
            $data['token'] = sha1(time().auth()->user()->id);
            $coop = tenant();
            $data['agence_id'] = $coop->agence_id;
            $data['representation_id'] = $coop->representation_id;

            $data['saison_id'] = $this->_saison->id;
            $data['domaine_id'] = $coop->domaine_id;
            $data['user_id'] = auth()->user()->id;
            $data['name'] = str_pad($coop->id.date('ymdhi').$data['exploitant_id'],'0',STR_PAD_LEFT);
            $data['montant'] = $data['pu'] * $data['quantity'];
            Entree::create($data);
        });

        $data = $request->all();
        $stock = EntrepotGamme::where('entrepot_id',$data['entrepot_id'])->where('gamme_id',$data['gamme_id'])->where('tenant_id',$tenant->id)->where('saison_id',$this->_saison->id)->first();
        if(!$stock){
            $stock = EntrepotGamme::create([
                'entrepot_id'=>$data['entrepot_id'],
                'gamme_id'=>$data['gamme_id'],
                'agence_id'=>$tenant->agence_id,
                'representation_id'=>$tenant->representation_id,
                'tenant_id'=>$tenant->id,
                'saison_id'=>$this->_saison->id,
            ]);
        }
        $stock->quantity = $stock->quantity + $data['quantity'];
        $stock->save();
        }else{
            $data = $request->all();
            $data['token'] = sha1(time().auth()->user()->id);
            $data['agence_id'] = tenant()->agence_id;
            $data['representation_id'] = tenant()->representation_id;
            $data['entrepot_id'] = auth()->user()->entrepot_id;
            $data['saison_id'] = $this->_saison->id;
            $data['domaine_id'] = tenant()->domaine_id;
            $data['user_id'] = auth()->user()->id;
            $data['name'] = str_pad(tenant()->id.date('ymdhi').$data['exploitant_id'],'0',STR_PAD_LEFT);
            $data['montant'] = $data['pu'] * $data['quantity'];
            Entree::create($data);
            $stock = EntrepotGamme::where('entrepot_id',$data['entrepot_id'])->where('gamme_id',$data['gamme_id'])->where('tenant_id',tenant()->id)->where('saison_id',$this->_saison->id)->first();
            if(!$stock){
                $stock = EntrepotGamme::create([
                    'entrepot_id'=>$data['entrepot_id'],
                    'gamme_id'=>$data['gamme_id'],
                    'agence_id'=>tenant()->agence_id,
                    'representation_id'=>tenant()->representation_id,
                    'tenant_id'=>tenant()->id,
                    'saison_id'=>$this->_saison->id,
                ]);
            }
            $stock->quantity = $stock->quantity + $data['quantity'];
            $stock->save();
        }
        Session::flash('success','Enregistrement créé avec succès!');
        return redirect(route('admin.entrees.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Projet  $projet
     * @return \Illuminate\Http\Response
     */
	public function show($token,$tenant_id=0)
	{
        if($tenant_id){
            $tenant = Tenant::find($tenant_id);
            $item = $tenant->run(function()use($token){
                return Entree::where('token',$token)->first();
            });
        }else{
            $item = Entree::where('token',$token)->first();
        }
       // $item = Entree::where('token',$token)->first();
        $caisses = Caisse::where('active',1)->where('tenant_id',tenant()->id)->get();
        $wallets = Wallet::where('active',1)->where('tenant_id',tenant()->id)->get();
        //dd($wallets);
        return view('Tenant/Admin.Entrees.show',compact('item','caisses','wallets','tenant_id'));
	}

    public function getPaiements(){
        $items = Paiement::orderBy('created_at','DESC')->get();
        return view('Tenant/Admin.Entrees.paiements',compact('items'));
    }

    public function addPaiement(Request $request){
        if($request->tenant_id){
            $tenant = Tenant::find($request->tenant_id);
           $item = $tenant->run(function(){
                return Entree::find(request()->entree_id);
            });
        }else{
            $item = Entree::find(request()->entree_id);
        }

       // $item = Entree::find(request()->entree_id);

        $data = [
            'entree_id'=>$item->id,
            'montant'=>request()->montant,
            'exploitant_id'=>$item->exploitant_id,
            'mode_paiement_id'=>$request->mode_paiement_id,
            'user_id'=>auth()->user()->id,
            'saison_id'=>$this->_saison->id,
            'tenant_id'=>$request->tenant_id??0,
            'token'=>sha1(time().auth()->user()->id),
        ];
        if($request->wallet_id){
            $wallet = Wallet::find(request()->wallet_id);
            $data['wallet_id'] = $request->wallet_id;
            $data['compte'] = $request->phone;
            $wallet->montant = $wallet->montant - request()->montant;
            if($wallet->montant>=0){
                $wallet->save();
                $paiement = Paiement::create($data);
               // ProcessPaymentJob::dispatch($paiement, tenant());
                $ps = new PaymentService(tenant());
                $ps->processPayment($paiement);

            }else{
                Session::flash('error','Solde du wallet insuffisant pour effectuer ce paiement!');
                return back();
            }
        }else{
            if($request->caisse_id){
                $caisse = Caisse::find(request()->caisse_id);
                $data['caisse_id'] = $request->caisse_id;
                $caisse->montant = $caisse->montant - request()->montant;
                if($caisse->montant>=0){
                    $caisse->save();
                    Paiement::create($data);
                }else{
                    Session::flash('error','Solde de la caisse insuffisant pour effectuer ce paiement!');
                    return back();
                }

            }
        }

        Session::flash('success','Paiement effectué avec succès!');
        return back();
    }


}

