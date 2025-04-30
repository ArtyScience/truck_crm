<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignsUserEmail extends Model
{
    use HasFactory;

    protected $table = 'campaigns_user_emails';
    protected $fillable = ['user_id', 'email'];
}
