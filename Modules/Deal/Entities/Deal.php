<?php

namespace Modules\Deal\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    use HasFactory;
    protected $table = 'deals';
    protected $fillable = [
        'user_id',
        'lead_id',
        'company_id',
        'status_id',
        'income',
        'outcome',
        'currency',
    ];

    public static function dealEdit(int $deal_id)
    {
       return Deal::select('deals.id', 'deals.lead_id', 'deals.income', 'leads.name as company_contact',
            'deals.outcome','deals_details.pick_up_date', 'deals_details.delivery_date',
            'deals_details.equipment_type', 'deals_details.shipment_type', 'deals.currency',
            'deals_details.deal_type', 'deals_options.pick_up', 'deals_options.delivery', 'deals_options.haz', 'deals_options.tarp', 'deals_options.temp',
            'deals_details.comment', 'leads.name as lead_tmp')
            ->leftJoin('deal_locations', 'deal_locations.deal_id', '=', 'deals.id')
            ->leftJoin('deals_details', 'deals_details.deal_id', '=', 'deals.id')
            ->leftJoin('deals_options', 'deals_options.deal_id', '=', 'deals.id')
            ->leftJoin('leads', 'leads.id', '=', 'deals.lead_id')
            ->where('deals.id', $deal_id)->first()->toArray();
    }

    public static function dealResponse(array $deal): array
    {
        $deal['income'] = (string)$deal['income'];
        $deal['outcome'] = (string)$deal['outcome'];
        $deal['pick_up'] = (bool)$deal['pick_up'];
        $deal['delivery'] = (bool)$deal['delivery'];
        $deal['haz'] = (bool)$deal['haz'];
        $deal['tarp'] = (bool)$deal['tarp'];
        $deal['temp'] = (bool)$deal['temp'];

        return $deal;
    }
}
