<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "user_id" => $this->user_id,
            "notification_id" => $this->notification_id,
            "title" => $this->notification->title,
            "body" => $this->notification->body,
            "read" => (boolean)$this->read,
            "description" => $this->notification->description,
            "type" => $this->notification->type,
            "place_id" => $this->notification->place_id,
            "created_at" => $this->notification->created_at,
            "updated_at" => $this->notification->updated_at,

        ];
    }
}
