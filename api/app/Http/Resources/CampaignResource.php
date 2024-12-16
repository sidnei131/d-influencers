<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CampaignResource extends JsonResource
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
            'name' => $this->name,
            'budget' => $this->budget,
            'desc' => $this->desc,
            'init_date' => $this->init_date,
            'end_date' => $this->end_date,
            'influencers' => InfluencerResource::collection($this->whenLoaded('influencers')),
        ];
    }
}
