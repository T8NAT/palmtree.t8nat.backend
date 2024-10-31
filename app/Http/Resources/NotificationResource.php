<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = json_decode($this->data,true);
        return [
            'id' => $this->id,
            'title' => isset($data['title']) ? $data['title'] : null,
            'body'  => isset($data['body'])  ? $data['body'] : null,
            'created_at' => date('Y-m-d H:i:s', strtotime($this->created_at))
        ];
    }
}
