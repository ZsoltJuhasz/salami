<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Product extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "price_kg" => $this->price_kg,
            "cid" => $this->cid,
            "production_time" => $this->production_time,
            "created_at" => $this->created_at->format( "m/d/Y" ),
            "updated_at" => $this->updated_at->format( "m/d/Y" )
        ];
    }
}
