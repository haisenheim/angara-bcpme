<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProduitListResource extends JsonResource
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
            'code'=>$this->code,
            'title'=>$this->code .' '.$this->name,
            'text'=>$this->code .' '.$this->name,

            //$this->children?
            //'subs'=>ProduitListResource::collection($this->children),
        ];
        if($this->children->count()){
            $data['subs'] = ProduitListResource::collection($this->children);
            $data['children'] = ProduitListResource::collection($this->children);
            $data['state'] ='closed';
        }
        return $data;
    }
}
