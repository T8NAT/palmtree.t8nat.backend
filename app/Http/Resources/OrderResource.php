<?php

namespace App\Http\Resources;

use App\Helpers\Helpers;
use Illuminate\Http\Request;
use App\Http\Resources\ProposalResource;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'company_id'  => $this->company_id,
            'delivery'    => $this->whenLoaded('delivery'),
            'proposal'    => ProposalResource::collection($this->whenLoaded('proposals')),
            'location'    => [
                'locationLat' => $this->location_lat,
                'locationLng' => $this->location_lng,
                'locationName'=> $this->location_name,
                'location_full_address' => $this->location_full_address,
        
            ],
            'destination'    => [
                'destinationLat' => $this->destination_lat,
                'destinationLng' => $this->destination_lng,
                'destinationName'=> $this->destination_name,
                'destination_full_address' => $this->destination_full_address,
            ],
            'seller_name' => $this->seller_name,
            'customer_name' => $this->customer_name,
            'customer_notes' => $this->customer_notes,
            'order_status'   => $this->order_status,
            'instructions'   => $this->instructions,
            'attachment_url' => $this->attachment ? asset('storage/attachments/' . $this->attachment) : null,
            //'attachment_type' => $this->attachment ? mime_content_type(public_path('storage/attachments/' . $this->attachment)) : null,
            'created_at'     => $this->created_at,
            'updated_at'     => $this->updated_at,
            'unique_id'      => $this->unique_id,
            'insrtuctions'   => Helpers::localInsrtuctions($this->insrtuctions)
        ];
    }
}
