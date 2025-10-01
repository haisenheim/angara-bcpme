<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CooperativeListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'agence'=>$this->agence?->name,
            'representation'=>$this->representation?->name,
            'commune'=>$this->arrondissement?->name,
            'departement'=>$this->departement?->name,
            'region'=>$this->region?->name,
            'taille'=>$this->taille,
            'forme'=>$this->forme?->name,
            'email'=>$this->email,
            'phone'=>$this->phone,
            'filiere'=>$this->domaine?->name,
            'secteur'=>$this->secteur?->name,
            'union'=>$this->parent?->name,
            'is_union'=>$this->is_union?'OUI':'NON',
            'token'=>$this->token,
        ];
    }
}
