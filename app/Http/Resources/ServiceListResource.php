<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceListResource extends JsonResource
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
            'title'=>$this->name .' ('.$this->type->name.')',
            'type'=>$this->type->name
        ];
        return $data;
    }
}
