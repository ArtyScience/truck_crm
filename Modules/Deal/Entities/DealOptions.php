<?php

namespace Modules\Deal\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DealOptions extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'deals_options';
    protected $fillable = [
        'deal_id',
        'pick_up',
        'delivery',
        'haz',
        'tarp',
        'temp',
    ];
}
