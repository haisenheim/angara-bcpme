<?php

namespace App\Http\Controllers\Sectoriel;

use App\Http\Controllers\Ca\RequestController as CaRequestController;
use App\Models\Structuration\Request as StructurationRequest;

class RequestController extends CaRequestController
{
    public function index()
    {
        $items = StructurationRequest::orderBy('created_at', 'DESC')->where('agence_id', auth()->user()->agence_id)->get();

        return view('Sectoriel.Requests.index')->with(compact('items'));
    }
}
