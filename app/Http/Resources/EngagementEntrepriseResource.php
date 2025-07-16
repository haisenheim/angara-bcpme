<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EngagementEntrepriseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $data = [
            'id'=>$this->id,
            'name'=>$this->engagement?->name,
            'banque'=>$this->banque?->name,
            'entreprise'=>$this->entreprise?->name,
            'montant'=>$this->montant,
            'encours_montant'=>$this->encours_montant,
            'encours_part'=>$this->encours_part,
            //'created_at'=>$this->created_at,
            //'updated_at'=>$this->updated_at,
            //'parent_id'=>$this->parent_id,
            //'is_title'=>$this->is_title,
            //'is_leaf'=>$this->is_leaf,
            //'niveau'=>$this->niveau,
        ];
        return $data;
    }
}
