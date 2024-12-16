<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CampaignInfluencer extends Pivot
{
    use HasFactory;

    protected $fillable = [
        'campaigns_id',
        'influencers_id',
    ];
}
