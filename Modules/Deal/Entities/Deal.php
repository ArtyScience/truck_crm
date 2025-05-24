<?php

namespace Modules\Deal\Entities;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\Leads\Entities\Lead;
use phpseclib3\Net\SFTP;

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

    public static function getDealStatistics(): mixed
    {
        $role = User::getUserRole();

        $statistics = Deal::selectRaw('
                    EXTRACT(DOW FROM created_at) AS day,
                    COUNT(*) as total_rows')
            ->groupBy('day')->orderBy('day', 'ASC');

        if ($role !== 'ADMIN') {
            $statistics = $statistics->where(
                'deals.user_id', Auth::user()->id);
        }

        $statistics = $statistics->where('deals.user_id', Auth::user()->id)
            ->whereBetween('created_at', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek() ])->get();

        return $statistics;
    }

    public static function getStatusesCount($status_id)
    {
        $role = User::getUserRole();

        $statuses = Deal::where('status_id', $status_id);

        if ($role !== 'ADMIN') {
            $statuses = $statuses->where('user_id', Auth::id());
        }

        return $statuses->count();
    }

}
