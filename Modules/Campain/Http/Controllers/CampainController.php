<?php
/*TODO: REFACTOR ENTIRE CONTROLLER -> Implement Design Patterns*/
namespace Modules\Campain\Http\Controllers;

use App\Models\CampaignSchedule;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Octane\Exceptions\DdException;
use Modules\ApiLogic\Logic\SmartLeadAPI;
use Modules\Campain\Entities\Campaign;
use Modules\Campain\Entities\CampaignLeads;
use Modules\Campain\Entities\CampaignStatus;
use Modules\Campain\Entities\CampaignTemplates;
use Modules\Campain\Http\Requests\CampaignStoreRequest;
use Modules\Campain\Http\Requests\StartCampaignSchedullerRequest;
use Modules\Campain\Http\Requests\StoreCampaignTemplateRequest;
use Modules\Core\Http\Controllers\CoreController;
use Modules\Leads\Entities\Lead;

class CampainController extends CoreController
{
    private array $statuses_default = [
        'attach_leads' => false,
        'save_email' => false,
        'set_time' => false,
    ];
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $api_campains = Campaign::select('campaigns.id', 'name', 'campaigns.status',
            'campaign_statuses.status as steps_statuses')
            ->leftJoin('campaign_statuses', 'campaign_statuses.campaign_id', '=', 'campaigns.id')
            ->where('user_id', Auth::id())->get();

        $api_campains = $api_campains->transform(function ($item) {
            if ($item->steps_statuses !== null) {
                $statuses = json_decode($item->steps_statuses, true);
                $item->steps_statuses = [
                    'attach_leads' => $statuses['attach_leads'],
                    'save_email' => $statuses['save_email'],
                    'set_time' => $statuses['set_time'],
                ];
            } else {
                $item->steps_statuses = $this->statuses_default;
            }
            return $item;
        });

        return view('campain::index', compact('api_campains'));
    }

    public function geCampaignLeads(int $id): JsonResponse
    {
        $leads = Lead::select('leads.id', 'leads.name')
            ->leftJoin('campaign_leads', 'campaign_leads.lead_id', '=', 'leads.id')
            ->where('leads.user_id', '=', Auth::id())
            ->where('campaign_leads.campaign_id', '=', $id)
            ->get();

        return response()->json($leads);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('campain::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CampaignStoreRequest $request): JsonResponse
    {
        $data = $request->only('name');
        $data['client_id'] = null;
        $smart_lead_api = new SmartLeadApi($data);
        $response = $smart_lead_api->fetchData('/campaigns/create');
        $campaign = [
            'name' => $response['name'],
            'user_id' => Auth::user()->id,
            'campaign_id' => $response['id']
        ];
        $created_campaign = Campaign::create($campaign);
        CampaignStatus::create([
            'campaign_id' => $created_campaign->id,
            'status' => json_encode($this->statuses_default)
        ]);

        return response()->json([
            'id' => $created_campaign->id,
            'name' => $created_campaign->name,
        ]);
    }

    public function saveCampaignLeads(Request $request): JsonResponse
    {
        $leads = $request->get('attached_lead');
        $campaign_id = $request->get('campaign')['id'];
        $leads_ids = [];
        foreach ($leads as $lead) {
            CampaignLeads::create([
                'lead_id' => $lead['id'],
                'campaign_id' => $campaign_id,
            ]);
            $leads_ids[] = $lead['id'];
        }

        $leads_data = Lead::select('id', 'name', 'phone', 'email')
            ->whereIn('id', $leads_ids)->get()->toArray();

        $leads_for_api = [];
        foreach ($leads_data as $lead_data) {
            $lead_full_name = explode(' ', $lead_data['name']);
            $leads_for_api[] = [
                'first_name' => isset($lead_full_name[0]) ? $lead_full_name[0] : $lead_data['name'],
                "last_name"=> isset($lead_full_name[1]) ? $lead_full_name[1] : $lead_data['name'],
			    "email" => $lead_data['email'],
			    "phone_number" => $lead_data['phone'],
            ];
        }
        $data_for_api['lead_list'] = $leads_for_api; unset($leads_for_api);

        $api = new SmartLeadAPI($data_for_api);
        $api_campaign = Campaign::select('campaign_id')->where('id', $campaign_id)->first();
        $response = $api->fetchData('/campaigns/' . $api_campaign->campaign_id . '/leads');

        if (isset($response['ok']) && $response['ok']) {
            $leads_data = Lead::select('id', 'name')->whereIn('id', $leads_ids)->get();
            return response()->json(['api_response' => $response, 'attached_leads' => $leads_data]);
        } else {
            Lead::destroy($leads_ids);
            return response()->json(['api_response' => $response, 'attached_leads' => []]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        $campaign = Campaign::where('id', $id)->first();
        if (is_null($campaign)) {
            return response()->json(['error' => 'Campaign not found'], 404);
        }
        $api = new SmartLeadApi();
        $response = $api->fetchData('/campaigns/' . $campaign->campaign_id, 'delete');
        if ($response['ok']) {
            $campaign->delete();
            return response()->json(['message' => 'Campaign deleted successfully']);
        } else {
            return response()->json(['error' => 'Campaign was not deleted, please try again']);
        }
    }

    public function removeCampaignLead(int $id, int $campaign_id): JsonResponse
    {
        $lead = Lead::select('id', 'email')->where('id', $id)->first();
        $campaign = Campaign::where('id', $campaign_id)->first();

        $api = new SmartLeadApi();
        $response = $api->fetchData('/campaigns/' . $campaign->campaign_id . '/leads', 'get');

        $api_leads = $response['data'];
        $api_lead_id = null;

        foreach ($api_leads as $api_lead) {
            if ($api_lead['lead']['email'] == $lead->email) {
                $api_lead_id = $api_lead['lead']['id'];
            }
        }

        if (!is_null($api_lead_id)) {
            $api = new SmartLeadApi();
            $delete_response = $api->fetchData('/campaigns/'
                . $campaign->campaign_id . '/leads/' . $api_lead_id, 'delete');
        }

        if (isset($delete_response) && $delete_response['ok']) {
            CampaignLeads::where('lead_id', $id)
                ->where('campaign_id', $campaign_id)->delete();

            return response()->json(['message' => 'Lead removed successfully']);
        }

        return response()->json(['error' => 'API: Error removing lead'], 500);
    }

    public function saveCampaignTemplate(StoreCampaignTemplateRequest $request): JsonResponse
    {
        $template = $request->all();
        $campaign = Campaign::where('id', $template['campaign_id'])->first();
        $api = new SmartLeadApi($this->templateSettings($template['subject'], $template['body']));
        $api_response = $api->fetchData('/campaigns/' . $campaign->campaign_id . '/sequences');

        if (isset($api_response['ok']) && $api_response['ok']) {
            CampaignTemplates::where('campaign_id', $template['campaign_id'])->delete();
            CampaignTemplates::create($template);
        } else {
            return response()->json(['error' => 'Error saving template'], 500);
        }

        return response()->json(
            ['message' => 'Template was saved',
             'template' => $template]
        );
    }

    private function templateSettings(string $subject, string $body): array
    {
        $data = [
            "sequences" => [
                [
                    "seq_number" => 1,
                    "seq_delay_details" => [
                        "delay_in_days" => 1
                    ],
                    "variant_distribution_type" => "MANUAL_EQUAL",
                    "lead_distribution_percentage" => 40,
                    "winning_metric_property" => "OPEN_RATE",
                    "seq_variants" => [
                        [
                            "subject" => $subject,
                            "email_body" => $body,
                            "variant_label" => "A",
                            "variant_distribution_percentage" => 20
                        ],
                    ]
                ]
            ]
        ];

        return $data;
    }

    public function getCampaignTemplate(int $campaign_id): JsonResponse
    {
        $campaign = CampaignTemplates::where('campaign_id', $campaign_id)->first();
        $response = [
            'body' => $campaign->body ?? '',
            'subject' => $campaign->subject ?? ''
        ];
        return response()->json($response);
    }

    /**
     * @throws DdException
     */
    public function attachEmailToCampaign(Request $request): JsonResponse
    {
        $api_campaign_id = Campaign::select('campaign_id')
            ->where('id', $request->campaign_id)->first()->campaign_id;

        $api = new SmartLeadApi();
        $api->setRequestSettings('&offset=0&limit=10');
        $response = $api->fetchData('/email-accounts', 'get');

        $email_id = $response[0]['id']; unset($response);
        $api = new SmartLeadApi(['email_account_ids' => [$email_id]]);
        $response = $api->fetchData('/campaigns/' . $api_campaign_id . '/email-accounts');

        return response()->json($response);
    }

    public function storeCampaignScheduller(StartCampaignSchedullerRequest $request)
    {
        $data = $request->all()['form'];

        $data['days_of_the_week'] = array_values($this->parseDays($data['days']));

        $data['start_hour'] = $data['start_hour']['hours'] . ':' . $data['start_hour']['minutes'];
        $data['end_hour'] = $data['end_hour']['hours'] . ':' . $data['end_hour']['minutes'];

        if ($data['start_hour'] >= $data['end_hour']) {
            return response()->json(['errors' =>
                [['Start time could not be more than end time']]], 422);
        }

        $present_time = new Carbon();

        $api_data = [
            'timezone' => $data['timezone'],
            'days_of_the_week' => $data['days_of_the_week'],
            'start_hour' => $data['start_hour'],
            'end_hour' => $data['end_hour'],
            "min_time_btw_emails" => 10,
            "max_new_leads_per_day" => 20,
	        "schedule_start_time" => $present_time->toDateTimeLocalString(),
        ];

        $api = new SmartLeadApi($api_data);

        $api_campaign_id = Campaign::select('campaign_id')
            ->where('id', $request->campaign_id)->first()->campaign_id;
        $response = $api->fetchData('/campaigns/' . $api_campaign_id . '/schedule');

        if ($response['ok']) {
            $storage_data = $request->all()['form'];
            $start_hour = $storage_data['start_hour'];
            $end_hour = $storage_data['end_hour'];

            $new_schedule = [
                'timezone' => $storage_data['timezone'],
                'campaign_id' => $request->campaign_id,
                'days_of_the_week' => json_encode($storage_data['days']),
                'start_hour' => Carbon::now()->setTime($start_hour['hours'], $start_hour['minutes']),
                'end_hour' => Carbon::now()->setTime($end_hour['hours'], $end_hour['minutes']),
                'schedule_start_time' => Carbon::now()
            ];

            CampaignSchedule::where('campaign_id', $request->campaign_id)->delete();
            CampaignSchedule::create($new_schedule);

            return response()->json(['message' => 'Scheduler set up successfully']);

        } else {
            return response()->json(['error' => 'Error saving data'], 500);
        }
    }

    public function getCampaignScheduller($campaign_id): JsonResponse
    {
        $response = CampaignSchedule::select('timezone', 'days_of_the_week as days', 'start_hour', 'end_hour')
            ->where('campaign_id', $campaign_id)->get();

        $response_parsed = $response->transform(function ($item) use ($response) {
            $start_hour = explode(":", $item->start_hour);
            $end_hour = explode(":", $item->end_hour);
            $item->start_hour = ["hours" => $start_hour[0], "minutes" => $start_hour[1]];
            $item->end_hour = ["hours" => $end_hour[0], "minutes" => $end_hour[1]];

            return $item;
        });

        if (!empty($response_parsed->toArray()) && isset($response_parsed->toArray()[0])) {
            return response()->json(['campaign_scheduller' => $response_parsed->toArray()[0]]);
        }

        return response()->json(['error' => 'Scheduller not found'], 404);
    }

    public function startCampaign(Request $request)
    {
        $campaign_id = $request->get('id');
        $api_campaign = Campaign::select('*')
            ->where('id', $campaign_id)->first();

        if (!CampaignLeads::where('campaign_id', $campaign_id)->exists()) {
            return response()->json(['message' => 'Campaign Leads not found'], 404);
        }

        if (!CampaignTemplates::where('campaign_id', $campaign_id)->exists()) {
            return response()->json(['message' => 'Template not found'], 404);
        }

        if (!CampaignSchedule::where('campaign_id', $campaign_id)->exists()) {
            return response()->json(['message' => 'Scheduller not found'], 404);
        }

        $api_campaign->status ^= 1;
        $api_status = $api_campaign->status ? 'START' : 'PAUSED';

        $api = new SmartLeadApi(['status' => $api_status]);
        $response = $api->fetchData('/campaigns/' . $api_campaign->campaign_id . '/status');

        if ($response['ok']) {
            Campaign::where('id', $campaign_id)->update(['status' => $api_campaign->status]);

            return response()->json([
                'message' => $api_campaign->status ?
                    'Campaign Started' : 'Campaign Paused',
                'status' => $api_campaign->status
            ]);
        } else {
            return response()->json(['message' => 'Campaign start API ERROR'], 500);
        }
    }

    private function parseDays($days): array
    {
        $days_numbers = [ "mon" => 0, "tue" => 1, "wedn" => 2,
            "thur" => 3, "fry" => 4, "sat" => 5, "sun" => 6];

        foreach ($days as $day_short_name => $day) {
            if ($days[$day_short_name]) {
                $days[$day_short_name] =
                    $days_numbers[$day_short_name];
            } else {
                unset($days[$day_short_name]);
            }
        }
        return $days;
    }

    public function updateCampaignStatus(Request $request): JsonResponse
    {
        $statuses = CampaignStatus::where('campaign_id', $request->get('campaign_id'))->first();
        $old_statuses = json_decode($statuses->status, true);
        $updated_statuses = array_merge($old_statuses, $request->status_update);

        CampaignStatus::where('campaign_id', $request->get('campaign_id'))
            ->update(['status' => $updated_statuses]);

        $response = CampaignStatus::select('status')
            ->where('campaign_id', $request->get('campaign_id'))->first();

        return response()->json(['statuses' => json_decode($response->status, true)]);
    }

    public function getCampaignStatistics($campaign_id)
    {
        $campaign_id = Campaign::where('id', $campaign_id)->first()->campaign_id;

        $api = new SmartLeadApi();
        $response = $api->fetchData('/campaigns/' . $campaign_id . '/statistics', 'get');

        $opened = 0;
        $clicked = 0;
        $declined = 0;

        if (isset($response['data'])) {
            foreach ($response['data'] as $statistic) {
                $opened += $statistic['open_count'];
                $clicked += $statistic['click_count'];
                $declined += (int)$statistic['is_bounced'];
            }
        }

        $response = [
            [
                'x' => 'Opened',
                'y' => $opened
            ],
            [
                'x' => 'Clicked',
                'y' => $clicked
            ],
            [
                'x' => 'Declined',
                'y' => $declined
            ],
        ];
        return response()->json(['campaign_statistics' => $response]);
    }
}
