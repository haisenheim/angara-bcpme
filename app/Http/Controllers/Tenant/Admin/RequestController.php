<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Http\Controllers\ExtendedController;
use App\Models\Structuration\Caisse;
use App\Models\Structuration\Wallet;
use App\Models\Structuration\Request as StructurationRequest;
use App\Models\User;
use App\Notifications\Structuration\RequestNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;

class RequestController extends ExtendedController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $items = StructurationRequest::orderBy('created_at','DESC')->where('tenant_id',tenant()->id)->get();
        return view('Tenant/Admin/Requests/index')->with(compact('items'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $items = Wallet::where('active',1)->where('tenant_id',tenant()->id)->get();
        $caisses = Caisse::where('active',1)->where('tenant_id',tenant()->id)->get();
        return view('Tenant/Admin/Requests/create')->with(compact('items','caisses'));
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
        $data['tenant_id'] = tenant()->id;
        $coop = tenant();
        if($data['wallet_id']){
            $wallet = Wallet::where('token',$data['wallet_id'])->first();
            $data['token'] = sha1(time().$wallet->id);
            $data['user_id'] = auth()->user()->id;
            $data['saison_id'] = $this->_saison->id;
            $data['wallet_id'] = $wallet->id;
            $data['agence_id'] = $coop->agence_id;
            $data['representation_id'] = $coop->representation_id;
            $req = StructurationRequest::create($data);
            Session::flash('success','Requete envoyée avec succès!');

        }

        if($data['caisse_id']){

            $wallet = Caisse::where('token',$data['caisse_id'])->first();
            //$data['cooperative_id'] = $wallet->cooperative_id;
            $data['token'] = sha1(time().$wallet->id);
            $data['user_id'] = auth()->user()->id;
            $data['saison_id'] = $this->_saison->id;
            $data['caisse_id'] = $wallet->id;
            $data['agence_id'] = $coop->agence_id;
            $data['representation_id'] = $coop->representation_id;
            $req = StructurationRequest::create($data);
            Session::flash('success','Requete envoyée avec succès!');
            //return redirect(route('admin.requests.index'));
        }

        //$user = User::where('agence_id',tenant('agence_id'))->first();
        $users = DB::connection('central_app_mysql')->table('users')
                        ->where('agence_id',tenant('agence_id'))
                        ->get();

        $u = $users->map(function($u){
            $user = new User();
            $user->email = 'alliages.technologies@gmail.com';
            $user->name = $u->name;
            $user->id = $u->id;
            return $user;
        });
        //dd($u);
        //$user->email = 'alliages.technologies@gmail.com';

        Notification::send($u,new RequestNotification($req));

        return redirect(route('admin.requests.index'));

        //return back();
    }


    public function store_(Request $request)
    {
        $data = $request->all();
        $coop = tenant();
        if($data['wallet_id']){
            $wallet = Wallet::where('token',$data['wallet_id'])->first();
            $data['token'] = sha1(time().$wallet->id);
            $data['user_id'] = auth()->user()->id;
            //$data['operateur_id'] = $wallet->type_id;
            $data['saison_id'] = $this->_saison->id;
            $data['wallet_id'] = $wallet->id;
            $data['agence_id'] = $coop->agence_id;
            $data['representation_id'] = $coop->representation_id;
            $req = StructurationRequest::create($data);
            //$req->token = sha1(time().$wallet->id);
           // $req->user_id = auth()->user()->id;
           // $req->saison_id = $this->_saison->id;
           // $req->wallet_id;
           // $req->save();
            Session::flash('success','Requete envoyée avec succès!');

        }

        if($data['caisse_id']){

            $wallet = Caisse::where('token',$data['caisse_id'])->first();
            //$data['cooperative_id'] = $wallet->cooperative_id;
            $data['token'] = sha1(time().$wallet->id);
            $data['user_id'] = auth()->user()->id;
            $data['saison_id'] = $this->_saison->id;
            $data['caisse_id'] = $wallet->id;
            $data['agence_id'] = $coop->agence_id;
            $data['representation_id'] = $coop->representation_id;
            $req = StructurationRequest::create($data);
            Session::flash('success','Requete envoyée avec succès!');
            //return redirect(route('admin.requests.index'));
        }

        //$user = User::where('agence_id',tenant('agence_id'))->first();
        $users = DB::connection('central_app_mysql')->table('users')
                        ->where('agence_id',tenant('agence_id'))
                        ->get();

        $u = $users->map(function($u){
            $user = new User();
            $user->email = 'alliages.technologies@gmail.com';
            $user->name = $u->name;
            $user->id = $u->id;
            return $user;
        });
        //dd($u);
        //$user->email = 'alliages.technologies@gmail.com';

        Notification::send($u,new RequestNotification($req));

        return redirect(route('admin.requests.index'));

        //return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Projet  $projet
     * @return \Illuminate\Http\Response
     */
	public function show($token)
	{

	}


}
