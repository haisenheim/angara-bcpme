<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProducteurListResource extends JsonResource
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
            'phone'=>$this->phone,
            'photo'=>$this->photo,
            'age'=>Carbon::parse($this->dtn)->age,
            'village'=>$this->village?->name,
            'cooperative'=> new CooperativeListResource($this->cooperative),
        ];
    }
}
