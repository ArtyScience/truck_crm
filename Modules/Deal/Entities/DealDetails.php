<?php

namespace Modules\Deal\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DealDetails extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'deals_details';
    protected $fillable = [
        'deal_id',
        'pick_up_location',
        'delivery_location',
        'pick_up_date',
        'delivery_date',
        'equipment_type',
        'shipment_type',
        'deal_type',
        'comment',
    ];
}
