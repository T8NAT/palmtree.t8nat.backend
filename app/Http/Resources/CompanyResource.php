<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
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
            'name'      => $this->name,
            'email'     => $this->email,
            'unique_id' => $this->unique_id,
            'main_address' => [
                'address' => $this->company_info->address,
                'city'    => $this->company_info->city,
                'street'  => $this->company_info->street,
                'neighbourhood' => $this->company_info->neighbourhood,
                'address_lat'   => $this->company_info->address_lat,
                'address_lng'   => $this->company_info->address_lng,
                'postalCode'    => $this->company_info->postalCode
            ],
            'sub_address' => [
                'sub_address' => $this->company_info->sub_address,
                'sub_city'    => $this->company_info->sub_city,
                'sub_street'  => $this->company_info->sub_street,
                'sub_neighbourhood' => $this->company_info->sub_neighbourhood,
                'sub_address_lat'   => $this->company_info->sub_address_lat,
                'sub_address_lng'   => $this->company_info->sub_address_lng,
                'sub_postalCode'    => $this->company_info->sub_postalCode
            ]
        ];
    }
}
