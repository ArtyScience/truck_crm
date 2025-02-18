<?php

namespace Modules\Campain\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignTemplates extends Model
{
    use HasFactory;
    protected $table = "campaign_templates";
    protected $fillable = ['campaign_id', 'subject' ,'body'];
}
