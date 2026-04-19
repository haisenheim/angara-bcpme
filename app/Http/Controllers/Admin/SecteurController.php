<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agence;
use App\Models\Secteur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;

class SecteurController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (! Schema::hasTable('secteurs')) {
            Session::flash('warning', 'Le referentiel des secteurs cooperatifs n\'est pas disponible.');
            return redirect()->route('admin.dashboard');
        }

        $items = Secteur::all();
        $agences = Agence::all();
        return view('/Admin/Secteurs/index',compact('items','agences'));
    }




    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (! Schema::hasTable('secteurs')) {
            Session::flash('warning', 'Le referentiel des secteurs cooperatifs n\'est pas disponible.');
            return redirect()->route('admin.dashboard');
        }

        $data = $request->all();
        $data['token'] = sha1(time().rand(0,99));
        $item = Secteur::create($data);
        Session::flash('success','Enregistrement effectué avec succès!');
        return back();

    }

    public function save(Request $request)
    {
        if (! Schema::hasTable('secteurs')) {
            Session::flash('warning', 'Le referentiel des secteurs cooperatifs n\'est pas disponible.');
            return redirect()->route('admin.dashboard');
        }

        $data = $request->data();
        $data['user_id'] = auth()->user()->id;
        $item = Secteur::updateOrCreate(['id'=>$request->id],$data);
        Session::flash('success','Enregistrement effectué avec succès!');
        return back();

    }

    /**
     * Display the specified resource.
     */
    public function show(string $token)
    {
        if (! Schema::hasTable('secteurs')) {
            Session::flash('warning', 'Le referentiel des secteurs cooperatifs n\'est pas disponible.');
            return redirect()->route('admin.dashboard');
        }

        $item = Secteur::where('token',$token)->first();

        return view('Admin/Secteurs/show',compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $token)
    {
        //
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
