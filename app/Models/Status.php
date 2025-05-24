<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Leads\Entities\LeadStatuses;

class Status extends Model
{
    use HasFactory;
    protected $table = 'statuses';
    protected $fillable = ['name'];

    const string CONCAT_WS_SYMBOL = " && ";

    public static function getStatuses()
    {
        $deal_subquery = self::dealsSubQuery();
        return Status::select('locations', 'deals_data', 'leads_data',
            'deals_details_data', 'statuses.name as status_name', 'statuses.id')
            ->leftJoinSub($deal_subquery, 'deals', function ($join) {
                $join->on('deals.status_id', '=', 'statuses.id');
            })
            ->orderBy('statuses.id', 'ASC')
            ->distinct()->get();
    }

    public static function getLeadsStatuses()
    {
        $deal_subquery = self::dealsSubQuery();

        return LeadStatuses::select(
            'locations',
            'deals_data',
            'leads_data',
            'deals_details_data',
            'lead_statuses.name as status_name',
            'lead_statuses.id'
        )
            ->leftJoinSub($deal_subquery, 'deals', function ($join) {
                $join->on('deals.status_id', '=', 'lead_statuses.id');
            })
            ->groupBy(
                'locations',
                'deals_data',
                'leads_data',
                'deals_details_data',
                'lead_statuses.name',
                'lead_statuses.id'
            )
            ->get();
    }

    private static function dealsSubQuery(): Builder
    {
        $role = User::getUserRole();

        $deals = DB::table('deals')
            ->select(
                'deals.status_id',
                DB::raw("
            STRING_AGG(
                DISTINCT CONCAT_WS(
                    '" . self::CONCAT_WS_SYMBOL . "',
                    deals.id, deals.income,
                    deals.created_at, deals.updated_at
                ),
                '||'
            ) AS deals_data
        "),
                DB::raw("
            STRING_AGG(
                DISTINCT CONCAT_WS(
                    '" . self::CONCAT_WS_SYMBOL . "',
                    deals_details.equipment_type, deals_details.shipment_type,
                    deals_details.pick_up_date
                ),
                '||'
            ) AS deals_details_data
        "),
                DB::raw("
            STRING_AGG(
                DISTINCT CONCAT_WS(
                    '" . self::CONCAT_WS_SYMBOL . "',
                    deal_locations.city, deal_locations.state,
                    deal_locations.type, deal_locations.deal_id
                ),
                '||'
            ) AS locations
        "),
                DB::raw("
            STRING_AGG(
                CONCAT_WS(
                    '" . self::CONCAT_WS_SYMBOL . "',
                    leads.name, leads.phone
                ),
                '||'
            ) AS leads_data
        ")
            )
            ->leftJoin('deals_details', 'deals_details.deal_id', '=', 'deals.id')
            ->leftJoin('deal_locations', 'deal_locations.deal_id', '=', 'deals.id')
            ->leftJoin('leads', 'leads.id', '=', 'deals.lead_id')
            ->orderBy('deals.status_id', 'ASC')
            ->groupBy('deals.status_id');


        if ($role !== 'ADMIN') {
            $deals = $deals->where('deals.user_id', Auth::user()->id);
        }

        return $deals;
    }
}
