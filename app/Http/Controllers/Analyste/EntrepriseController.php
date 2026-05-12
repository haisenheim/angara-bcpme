<?php

namespace App\Http\Controllers\Analyste;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class EntrepriseController extends Controller
{
    public function index()
    {
        $resp = Http::get('http://localhost:8080/entreprises');
        $items = json_decode($resp->body(), true);

        return view('/Analyste/Entreprises/index')->with(compact('items'));
    }

    public function create() {}

    public function store(Request $request)
    {
        return back();
    }

    public function setAnalyse()
    {
        $data = request()->except('_token');
        Http::post('http://localhost:8080/entreprise/dossier/analyse', $data);

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
