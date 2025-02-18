<?php

namespace Modules\Campain\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignStatus extends Model
{
    use HasFactory;
    protected $table = "campaign_statuses";
    protected $fillable = ['campaign_id', 'status'];
}
