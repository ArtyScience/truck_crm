<?php

namespace Modules\Deal\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DealLocation extends Model
{
    use HasFactory;

    protected $table = 'deal_locations';

    protected $fillable = [
        'deal_id',
        'type',
        'full_location',
        'city',
        'state',
        'country',
        'country_code',
        'postcode',
        'latitude',
        'longitude'
    ];

    public static function dealLocation(int $deal_id): array
    {
        return DealLocation::select('full_location', 'type')
            ->where('deal_id', $deal_id)->get()->toArray();
    }

}
