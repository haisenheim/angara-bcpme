<?php

namespace App\Http\Resources\Structuration;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CampagneResource extends JsonResource
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
            'date'=>$this->created_at->format('d/m/Y H:i'),
            'saison'=>$this->saison?->name,
            'producteur'=>$this->membre?->name,
            'verger'=>$this->verger?->name,
            'travaux'=> TravailVergerResource::collection($this->travaux),
            'traitements'=> TraitementVergerResource::collection($this->traitements),
            'visites'=>VisiteVergerResource::collection($this->visites),
            'morts'=>$this->morts,
            'replantations'=>$this->replantations,
            'etat'=>$this->etat,
            'observations'=>$this->observations,
            'rendement'=>$this->rendement,
            'token'=>$this->token,
        ];
    }
}
