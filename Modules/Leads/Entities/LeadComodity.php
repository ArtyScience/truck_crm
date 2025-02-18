<?php

namespace Modules\Leads\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadComodity extends Model
{
    use HasFactory;

    protected $table = 'lead_comodities';

    protected $fillable = ['lead_id', 'comodity_id'];
}
