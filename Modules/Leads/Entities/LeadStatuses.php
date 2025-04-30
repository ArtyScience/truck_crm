<?php

namespace Modules\Leads\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadStatuses extends Model
{
    use HasFactory;

    protected $table = 'lead_statuses';
    protected $fillable = ['name'];
}
