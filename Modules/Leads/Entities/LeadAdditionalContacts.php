<?php

namespace Modules\Leads\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadAdditionalContacts extends Model
{
    use HasFactory;

    protected $table = 'lead_additional_contacts';
    protected $fillable = ['lead_id', 'phone', 'email', 'location'];
}
