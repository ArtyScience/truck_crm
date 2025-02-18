<?php

namespace Modules\Leads\Entities;

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

        if (!is_null($lead_id)) $lead_query = $lead_query->where('leads.id', $lead_id);

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

        $lead_query = Lead::select('leads.id', 'company',
            'leads.name',
            'leads.email', 'leads.phone', 'leads.notes',
            'locations_list', 'comodities_list', 'leads.created_at',
            'leads.web_page', 'leads.company_volume', 'leads.status_id',)
            ->leftJoinSub($addresses_subquery, 'addresses', function ($join) {
                $join->on('addresses.lead_id', '=', 'leads.id');
            })
            ->leftJoinSub($comodities_subquery, 'lead_comodities_list', function ($join) {
                $join->on('lead_comodities_list.lead_id', '=', 'leads.id');
            })
            ->leftJoinSub($lead_companies_subquery, 'lead_companies', function ($join) {
                $join->on('lead_companies.lead_id', '=', 'leads.id');
            })
            ->where('user_id', Auth::user()->id)
            ->groupBy('leads.id')->orderBy('leads.id', 'DESC');

        return $lead_query;
    }

    private static function addressSubQuery(): Builder
    {
        return DB::table('lead_address as addresses')
            ->select(
                'addresses.lead_id',
                DB::raw('
                GROUP_CONCAT(
                         CONCAT_WS(
                            " - ", addresses.city, addresses.state
                        ), "||") AS locations_list'
                ),
            )
            ->groupBy('addresses.lead_id')->distinct();
    }

    private static function comoditySubQuery(): Builder
    {
        return DB::table('lead_comodities as lead_comodities_list')
            ->select(
                'lead_comodities_list.lead_id',
                DB::raw('GROUP_CONCAT(comodities.name SEPARATOR "||")
                                     AS comodities_list')
            )
            ->leftJoin('comodities', 'comodities.id',
                '=', 'lead_comodities_list.comodity_id')
            ->groupBy('lead_comodities_list.lead_id')->distinct();
    }

    private static function companySubQuery(): Builder
    {
        return  DB::table('lead_companies')
            ->select('lead_companies.lead_id',
                DB::raw('GROUP_CONCAT(companies.name SEPARATOR "||") AS company'))
            ->leftJoin('companies', 'companies.id', '=', 'lead_companies.company_id')
            ->groupBy('lead_companies.lead_id')->distinct();
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
        return Lead::select('state as x', DB::raw('COUNT(*) as y'))
            ->leftJoin('lead_address', 'lead_address.lead_id', '=', 'leads.id')
            ->where('leads.user_id', Auth::id())->where('state', '!=', null)
            ->where('state', '!=', '')->groupBy('state')->having('y', '>', 0)->get();
    }
}
