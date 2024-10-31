<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TrackingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'delivery'  => $this->whenLoaded('delivery'),
            'order'     => new OrderResource($this->whenLoaded('order')),
            'lat'       => $this->lat,
            'lng'       => $this->long,
            'created_at'=> date('Y-m-d h:i:s',strtotime($this->created_at))
        ];
    }
}
