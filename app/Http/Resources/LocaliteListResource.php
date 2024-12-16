<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LocaliteListResource extends JsonResource
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
            'title'=>$this->name,
            'children'=>[],
        ];
        if($this->children){
            $data['children'] = LocaliteListResource::collection($this->children);
            $data['subs'] = LocaliteListResource::collection($this->children);
            $data['nb']=$this->children->count();
        }
        return $data;
    }
}
