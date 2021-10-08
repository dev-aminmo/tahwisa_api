<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PlacesResource extends JsonResource
{

    public function toArray($request)
    {

        return $this->place_response;

    }
}
