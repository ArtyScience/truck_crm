<?php

namespace Modules\Campain\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignLeads extends Model
{
    use HasFactory;
    protected $table = 'campaign_leads';
    protected $fillable = ['campaign_id', 'lead_id'];
}
