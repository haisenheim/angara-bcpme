<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DossierListResource extends JsonResource
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
            'entreprise'=>$this->entreprise?->name,
            'programme'=>$this->programme?->name,
            'gestionnaire'=>$this->gestionnaire?->name,
            'agence'=>$this->agence?->name,
            'direction'=>$this->agence?->representation?->name,
            'produit'=>$this->entreprise?->produit?->name,
            'signataire'=>$this->programme?->signataire,
            'analyste'=>$this->analyste?->name,
            'created'=>$this->created_at->format('d/m/Y H:i'),
            'token'=>$this->token,
        ];
    }
}
