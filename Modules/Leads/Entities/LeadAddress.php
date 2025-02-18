<?php

namespace Modules\Leads\Entities;

use Database\Factories\LeadAddressFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeadAddress extends Model
{
    use HasFactory;
    protected $table = 'lead_address';
    protected $fillable = ['lead_id', 'location_name', 'country', 'city', 'state', 'zip'];

    protected static function newFactory(): LeadAddressFactory
    {
        return LeadAddressFactory::new();
    }
}
