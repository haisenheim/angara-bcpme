<?php

namespace App\Http\Controllers\Juridique;

use App\Http\Controllers\Prospect\ProspectReviewController;
use Illuminate\Http\Request;

class ProspectController extends ProspectReviewController
{
    public function index()
    {
        return $this->indexJuridique();
    }

    public function show(string $token)
    {
        return $this->showJuridique($token);
    }

    public function store(Request $request, string $token)
    {
        return $this->storeJuridique($request, $token);
    }
}
