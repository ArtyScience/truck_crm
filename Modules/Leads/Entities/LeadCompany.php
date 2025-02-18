<?php

namespace Modules\Leads\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadCompany extends Model
{
    use HasFactory;

    protected $table = 'lead_companies';
    protected $fillable = ['lead_id', 'company_id'];
}
