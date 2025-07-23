<?php

namespace App\Http\Controllers\Tenant\Payeur;

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
use App\Models\User;
use App\Services\PaymentService;
use Faker\Provider\ar_EG\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

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
        return view('Tenant/Payeur/Entrees/index');
    }

    public function fetchAll(){
        $items = Entree::orderBy('created_at','DESC')->where('entrepot_id',auth()->user()->entrepot_id)->get();
        $items = EntreeResource::collection($items);
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

        $coop = tenant();
        $agents = User::where('role_id',2)->get();
        $entrepots = Entrepot::all();
        $gammes = Gamme::where('domaine_id',$coop->domaine_id)->get();
        $exploitants = Membre::all();
        return view('Tenant/Payeur.Entrees.create',compact('agents','entrepots','gammes','exploitants'));
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
        $stock = EntrepotGamme::where('entrepot_id',$data['entrepot_id'])->where('gamme_id',$data['gamme_id'])->first();
        if(!$stock){
            $stock = EntrepotGamme::create([
                'entrepot_id'=>$data['entrepot_id'],
                'gamme_id'=>$data['gamme_id'],
                'agence_id'=>$coop->agence_id,
                'representation_id'=>$coop->representation_id,

            ]);
        }
        $stock->quantity = $stock->quantity + $data['quantity'];
        $stock->save();
        Session::flash('success','Enregistrement créé avec succès!');
        return redirect(route('payeur.entrees.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Projet  $projet
     * @return \Illuminate\Http\Response
     */
	public function show($token)
	{
        $item = Entree::where('token',$token)->first();
        $caisses = Caisse::where('active',1)->get();
        $wallets = Wallet::where('active',1)->get();
        //dd($wallets);
        return view('Tenant/Payeur.Entrees.show',compact('item','caisses','wallets'));
	}

    public function getPaiements(){
        $items = Paiement::orderBy('created_at','DESC')->get();
        return view('Tenant/Payeur.Entrees.paiements',compact('items'));
    }

    public function addPaiement(Request $request){
        $item = Entree::find(request()->entree_id);
        $data = [
            'entree_id'=>$item->id,
            'montant'=>request()->montant,
            'exploitant_id'=>$item->exploitant_id,
            'mode_paiement_id'=>$request->mode_paiement_id,
            'user_id'=>auth()->user()->id,
            'saison_id'=>$this->_saison->id,
            'token'=>sha1(time().auth()->user()->id),
        ];
        if($request->wallet_id){
            $wallet = Wallet::find(request()->wallet_id);
            $data['wallet_id'] = $request->wallet_id;
            $data['phone'] = $request->phone;
            $wallet->montant = $wallet->montant - request()->montant;
            if($wallet->montant>=0){
                $wallet->save();
                $paiement = Paiement::create($data);
                //ProcessPaymentJob::dispatch($paiement,tenant());
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
