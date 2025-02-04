<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EntreeResource extends JsonResource
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
            'pu'=>$this->pu,
            'gamme'=>$this->gamme?->name,
            'entrepot'=>$this->entrepot?->name,
            'montant'=>number_format($this->montant,0,',','.'),
            'producteur'=>$this->exploitant?->name,
            'agent'=>$this->agent?->name,
            'pu'=>number_format($this->pu,0,',','.'),
            'date'=>$this->created_at->format('d/m/Y'),
            'quantity'=>$this->quantity,
        ];
    }
}
