<?php

namespace App\Http\Controllers\Conformite;

use App\Http\Controllers\Prospect\ProspectReviewController;
use Illuminate\Http\Request;

class ProspectController extends ProspectReviewController
{
    public function index()
    {
        return $this->indexConformite();
    }

    public function show(string $token)
    {
        return $this->showConformite($token);
    }

    public function store(Request $request, string $token)
    {
        return $this->storeConformite($request, $token);
    }
}
