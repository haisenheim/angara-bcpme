<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaiementResource extends JsonResource
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
            'token'=>$this->token,
            'name'=>$this->name,
            'montant'=>number_format($this->montant,0,',','.'),
            'date'=>$this->created_at->format('d/m/Y'),
            'status'=>$this->status,
            'mode'=>$this->mode?->name,
            'caisse'=>$this->caisse?->name,
            'wallet'=>$this->wallet?->name,
            'compte'=>$this->compte,
            'justificatif'=>$this->justificatif,
            'phone'=>$this->phone,
            'active'=>$this->active,
            'user'=>$this->user?->name,
            'entree'=>$this->entree?->name,
            'saison_id'=>$this->saison_id,
            'exploitant'=>$this->exploitant?->name,
        ];
    }
}







