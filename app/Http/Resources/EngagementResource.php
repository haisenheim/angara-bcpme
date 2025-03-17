<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EngagementResource extends JsonResource
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
            'name'=>$this->name,
            'montant'=>$this->montant??0,
            'encours_montant'=>$this->encours_montant??0,
            'encours_part'=>$this->encours_part??0,
            'parent_id'=>$this->parent_id,
            'is_title'=>$this->is_title,
            'is_leaf'=>$this->is_leaf,
        ];
        if(!$this->is_leaf){
            $data['children'] = EngagementResource::collection($this->children);
        }
        return $data;
    }
}
