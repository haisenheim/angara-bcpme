<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EntrepriseListResource extends JsonResource
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
            'cnps'=>$this->cnps,
            'rccm'=>$this->rccm,
            'niu'=>$this->niu,
            'commune'=>$this->arrondissement?->name,
            'departement'=>$this->departement?->name,
            'region'=>$this->region?->name,
            'taille'=>$this->taille,
            'forme'=>$this->forme?->name,
            'email'=>$this->email,
            'phone'=>$this->phone,
            'caractere'=>$this->caractere,
            'systeme'=>$this->systeme,
            'capital'=>$this->capital,
            'personnel'=>$this->nb_personnel,
            'manager'=>$this->manager,
            'token'=>$this->token,
        ];
    }
}
