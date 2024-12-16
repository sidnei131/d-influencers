<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Influencer extends Model
{
    use HasFactory, SoftDeletes;

    protected $hidden = ['pivot', 'created_at', 'updated_at', 'deleted_at'];

    protected $fillable = [
        'name',
        'ig_user',
        'followers',
        'category',
    ];

    public function campaigns()
    {
        return $this->belongsToMany(Campaign::class, 'campaign_influencer', 'influencers_id', 'campaigns_id')
            ->using(CampaignInfluencer::class);
    }
}
