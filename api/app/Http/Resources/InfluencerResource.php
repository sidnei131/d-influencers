<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InfluencerResource extends JsonResource
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
            'ig_user' => $this->ig_user,
            'followers' => $this->followers,
            'category' => $this->category,
            'campaigns' => CampaignResource::collection($this->whenLoaded('campaigns')),
        ];
    }
}
