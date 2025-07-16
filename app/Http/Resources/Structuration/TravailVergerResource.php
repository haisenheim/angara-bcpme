<?php

namespace App\Http\Resources\Structuration;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TravailVergerResource extends JsonResource
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
            'date'=> Carbon::parse($this->jour)->format('d/m/Y H:i'),
            'type'=>$this->type?->name,
            'membre'=>$this->membre?->name,
            'methode'=>$this->methode,
            'description'=>$this->description,
        ];
    }
}
