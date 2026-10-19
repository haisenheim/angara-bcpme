<?php

namespace App\Http\Controllers\Gestionnaire;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class EntrepriseController extends Controller
{

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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        return back();
    }

    public function setAnalyse()
    {
        $data = request()->except('_token');
        // dd($data);
        $resp = Http::post('http://localhost:8080/entreprise/dossier/analyse', $data);

        return back();
    }

    public function enable($id)
    {

        return back();
    }

    public function disable($id)
    {

        return back();
    }
}
