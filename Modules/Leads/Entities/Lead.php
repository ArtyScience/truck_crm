<?php

namespace Modules\Leads\Entities;

use App\Models\User;
use Carbon\Carbon;
use Database\Factories\LeadFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Lead extends Model
{
    use HasFactory;
    protected $table = 'leads';
    protected $fillable = ['name', 'email', 'phone', 'notes', 'user_id',
                            'web_page', 'company_volume', 'status_id'];

    protected static function newFactory(): LeadFactory
    {
        return LeadFactory::new();
    }

    public static function getLeads(int $rows, $lead_id = null): mixed
    {
        $lead_query = self::leadQuery();

        if (!is_null($lead_id))
            $lead_query = $lead_query->where('leads.id', $lead_id);

        return $lead_query->paginate($rows);
    }

    public static function getLeadsByID(int|array $leads_id)
    {
        $lead_query = self::leadQuery();

        if (is_array($leads_id)) {
            $lead_query = $lead_query->whereIn('leads.id', $leads_id);
        }

        return $lead_query->paginate(100);
    }

    private static function leadQuery()
    {
        $addresses_subquery = self::addressSubQuery();
        $comodities_subquery = self::comoditySubQuery();
        $lead_companies_subquery = self::companySubQuery();

        $role = User::getUserRole();

        $lead_query = Lead::select(
            'leads.id',
            'lead_companies.company',
            'leads.name',
            'leads.email',
            'leads.phone',
            'leads.notes',
            'locations_list',
            'comodities_list',
            'leads.created_at',
            'leads.web_page',
            'leads.company_volume',
            'leads.status_id'
        )
            ->leftJoinSub($addresses_subquery, 'addresses', function ($join) {
                $join->on('addresses.lead_id', '=', 'leads.id');
            })
            ->leftJoinSub($comodities_subquery, 'lead_comodities_list', function ($join) {
                $join->on('lead_comodities_list.lead_id', '=', 'leads.id');
            })
            ->leftJoinSub($lead_companies_subquery, 'lead_companies', function ($join) {
                $join->on('lead_companies.lead_id', '=', 'leads.id');
            })
            ->groupBy(
                'leads.id',
                'lead_companies.company',
                'leads.name',
                'leads.email',
                'leads.phone',
                'leads.notes',
                'locations_list',
                'comodities_list',
                'leads.created_at',
                'leads.web_page',
                'leads.company_volume',
                'leads.status_id'
            )
            ->orderBy('leads.id', 'DESC');

        if ($role !== "ADMIN") {
            $lead_query = $lead_query->where('user_id', Auth::user()->id);
        }

        return $lead_query;
    }

    private static function addressSubQuery(): Builder
    {
        return DB::table('lead_address as addresses')
            ->select(
                'addresses.lead_id',
                DB::raw("
            STRING_AGG(
                CONCAT_WS(' - ', addresses.city, addresses.state),
                '||'
            ) AS locations_list
        "))->groupBy('addresses.lead_id');
    }

    private static function comoditySubQuery(): Builder
    {
        return DB::table('lead_comodities as lead_comodities_list')
            ->select(
                'lead_comodities_list.lead_id',
                DB::raw("
            STRING_AGG(comodities.name, '||') AS comodities_list
        ")
            )
            ->leftJoin('comodities', 'comodities.id', '=', 'lead_comodities_list.comodity_id')
            ->groupBy('lead_comodities_list.lead_id');
    }

    private static function companySubQuery(): Builder
    {
        return DB::table('lead_companies')
            ->select(
                'lead_companies.lead_id',
                DB::raw("STRING_AGG(companies.name, '||') AS company")
            )
            ->leftJoin('companies', 'companies.id', '=', 'lead_companies.company_id')
            ->groupBy('lead_companies.lead_id');
    }

    /*TODO: Implement in subquery*/
    private static function fullLocationSubquery(): Builder
    {
        return  DB::table('lead_address as full_location')
            ->select('full_location.lead_id',
                DB::raw('GROUP_CONCAT(full_location.location_name, full_location.city SEPARATOR ",")
                                    AS full_location_field'),
            ) ->groupBy('full_location.lead_id')->distinct();
    }

    public static function getLeadsCountByState(): Collection
    {
        return Lead::select('lead_address.state as x', DB::raw('COUNT(*) as y'))
            ->leftJoin('lead_address', 'lead_address.lead_id', '=', 'leads.id')
            ->where('leads.user_id', Auth::id())
            ->whereNotNull('lead_address.state')
            ->where('lead_address.state', '!=', '')
            ->groupBy('lead_address.state')
            ->havingRaw('COUNT(*) > 0')
            ->get();
    }

    public static function getLeadsStatistics()
    {
        $role = User::getUserRole();
        $statistics = Lead::selectRaw('
                    EXTRACT(DOW FROM created_at) AS day,
                    COUNT(*) as total_rows')
            ->groupBy('day')->orderBy('day', 'ASC');

        if ($role !== 'ADMIN') {
            $statistics = $statistics->where(
                'leads.user_id', Auth::user()->id);
        }

        $statistics = $statistics->whereBetween('created_at', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek() ])->get();

        return $statistics;
    }
}
