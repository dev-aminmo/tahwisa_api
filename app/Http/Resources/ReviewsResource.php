<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReviewsResource extends JsonResource
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
            'id' => $this->id,
            'vote' => floatval($this->vote),
            'comment' => $this->comment,
            'place_id' => $this->place_id,
            'user' => $this->user,
            'created_at' => date('Y-m-d', strtotime($this->created_at)),
            'updated_at' => date('Y-m-d', strtotime($this->updated_at)),
        ];
    }
}
