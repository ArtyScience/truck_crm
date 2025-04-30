<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignSchedule extends Model
{
    use HasFactory;

    protected $table = 'campaign_schedule';
    protected $fillable = ['days_of_the_week', 'campaign_id', 'start_hour', 'end_hour', 'timezone', 'schedule_start_time'];
}
