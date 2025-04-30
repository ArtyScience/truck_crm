<?php

/*TODO: Make entire controller refactoring, implement Desing Patterns!!!*/

namespace Modules\Deal\Http\Controllers;

use App\Http\Requests\DealCreateRequest;
use App\Models\Status;
use DateTime;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\CoreController;
use Modules\Core\Logic\Traits\LocationTrait;
use Modules\Deal\Entities\Deal;
use Modules\Deal\Entities\DealDetails;
use Modules\Deal\Entities\DealLocation;
use Modules\Deal\Entities\DealOptions;
use Modules\Deal\Logic\DealRepository;
use Modules\Leads\Entities\Lead;
use Modules\Task\Entities\TaskModel;

class DealController extends CoreController
{
    use LocationTrait;

    protected Deal $model;
    protected DealRepository $repository;
    public function __construct()
    {
        $this->model = new Deal();
        $this->repository = new DealRepository();
        $this->repository->setModel($this->model);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View|JsonResponse
    {
        $statuses = Status::getStatuses();
        $deals_collection = $this->collectionParser($statuses);
        $data['deals'] = [];
        foreach ($deals_collection as $deal) {
            $data['deals'] = array_merge($data['deals'], $deal);
        }
        $data['deals'] = json_encode($data['deals']);
        return view('deal::index', compact('data'));
    }

    public function all(): JsonResponse
    {
        $response = Deal::select('id as value', 'name as label')
                    ->where('user_id', Auth::id())->get();

        return response()->json($response);
    }

    private function collectionParser($collection)
    {

        $collection->transform(function ($status) {
            $merged_data = array();

            if (!is_null($status->deals_data)) {
                $status->deals_data = $this->parseDeals($status->deals_data);
                $status->deals_details_data = $this->parseDealsDetails($status->deals_details_data);
                $status->locations = $this->parseDealLocations($status->locations);
                $status->leads_data = $this->parseDealLeads($status->leads_data);
                $deal_counter = 0;

                $merged_data = array_map(function ($item)
                                    use (&$deal_counter, $status) {
                    $result = array_merge(
                        $item, $status->deals_details_data[$deal_counter] ?? [],
                        $status->locations[$deal_counter],
                        $status->leads_data[$deal_counter],
                    ); $deal_counter++;
                    return $result;
                }, $status->deals_data);
            }

            $parsed_merged_data = [];
            foreach ($merged_data as $key => $item) {
                $from = reset($item['from']);
                $to = reset($item['to']);
                $title = sprintf(
                    '%s %s - %s %s',
                    $from['city'], $from['state'],
                    $to['city'], $to['state'],
                );

                $parsed_merged_data[$status['status_name']][$key] = [
                    'id' => $item['id'],
                    'title' => $title,
                    'income' => $item['income'],
                    'created_at' => $item['created_at'],
                    'updated_at' => $item['updated_at'],
                    'tasks' => TaskModel::where('deal_id', $item['id'])
                                ->where('user_id', Auth::id())->count(),
                    'pick_up_date' => $item['pick_up_date'] ?? '',
                    'lead_name' => $item['lead_name'],
                    'lead_phone' => $item['lead_phone'],
                    'equipment' =>  isset($item['equipment_type']) ? strtoupper($item['equipment_type']) : '',
                    'shipment' => isset($item['shipment_type']) ? strtoupper($item['shipment_type']) : '',
                ];
            }


            if (empty($merged_data)) {
                $parsed_merged_data[$status['status_name']] = [];
                return $parsed_merged_data;
            }

            /*TODO: Refactor to ORDER by POSITION*/

            $array_to_reorder = $parsed_merged_data[$status['status_name']];
            usort($array_to_reorder, function ($a, $b) {
                return strtotime($b['created_at']) < strtotime($a['created_at']);
            });
            $parsed_merged_data[$status['status_name']] = $array_to_reorder;
            return $parsed_merged_data;
        });

        return $collection;
    }

    private function parseDealsDetails(string $deals_details): array
    {
        $columns_keys = ['equipment_type', 'shipment_type', 'pick_up_date'];
        return $this->dealQueryParser($deals_details, $columns_keys);
    }

    private function parseDeals(string $deals): array
    {
        $columns_keys = ['id', 'income', 'created_at', 'updated_at'];
        return $this->dealQueryParser($deals, $columns_keys);
    }

    private function parseTasks(string $deal_tasks): array
    {
        return $this->dealQueryParser($deal_tasks, ['tasks']);
    }
    private function parseDealLeads(string $deal_leads): array
    {
        $columns_keys = ['name', 'phone'];
        $response =  $this->dealQueryParser($deal_leads, $columns_keys);

        return array_map(function ($item) {
            return [
                'lead_name' => $item['name'],
                'lead_phone' => $item['phone']
            ];
        }, $response);
    }

    private function parseDealLocations(string $deal_locations): array
    {
        $columns_keys = ['city', 'state', 'type', 'deal_id'];
        $parsed_response = $this->dealQueryParser($deal_locations, $columns_keys);

        $deal_to_from = [];
        foreach ($parsed_response as $location) {
            $dealId = $location['deal_id'];
            if (!isset($deal_to_from[$dealId])) {
                $deal_to_from[$dealId] = [
                    'id' => $dealId,
                    'to' => [],
                    'from' => []
                ];
            }
            $deal_to_from[$dealId][$location['type']][] = [
                'city' => $location['city'],
                'state' => $location['state']
            ];
        }
        return array_values($deal_to_from);
    }

    private function dealQueryParser(string $deals, array $columns_keys): array
    {
        $deals_array = explode("||", $deals);
        foreach ($deals_array as $key => $deal) {
            $deal_clean_string = ltrim($deal, ',');
            $deal_components_array = explode('&&', $deal_clean_string);

            foreach ($deal_components_array as $columnd_id => $column_value) {
                if (count($deal_components_array) === count($columns_keys))
                    $deals_parsed[$key][$columns_keys[$columnd_id]] = trim($column_value);
            }
        }
        return $deals_parsed;
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('deal::create');
    }

    /*TODO: Implement Validation*/
    /**
     * Store a newly created resource in storage.
     */
    public function store(DealCreateRequest $request): JsonResponse
    {
        $deal_request = $request->all();
        $deal = Deal::create(
          [
              'user_id' => Auth::id(),
              'lead_id' => $deal_request['lead_id'],
              'status_id' => Status::first()->id,
              'income' => $deal_request['income'],
              'outcome' => $deal_request['outcome'],
              'currency' => $deal_request['currency'],
          ]
        );
        if (!is_null($deal)) {
            $pick_up_date = new DateTime($deal_request['pick_up_date']);
            $delivery_date = new DateTime($deal_request['delivery_date']);
            $details = $request->only('shipment_type', 'equipment_type', 'comment', 'deal_type');
            $details['deal_id'] = $deal->id;
            $details['pick_up_date'] = $pick_up_date->format('Y-m-d');
            $details['delivery_date'] = $delivery_date->format('Y-m-d');
            $details = DealDetails::create($details);

            $options = $request->only('pick_up', 'delivery', 'haz', 'tarp', 'temp');
            $options['deal_id'] = $deal->id;
            DealOptions::create($options);

            //save locations
            $pick_up_location = $this->locationParser($request->get('pick_up_location'));
            $pick_up_location['type'] = self::FROM;
            $pick_up_location['deal_id'] = $deal->id;
            $pick_up_location = DealLocation::create($pick_up_location);

            $delivery_location = $this->locationParser($request->get('delivery_location'));
            $delivery_location['type'] = self::TO;
            $delivery_location['deal_id'] = $deal->id;
            $delivery_location = DealLocation::create($delivery_location);

            $lead = Lead::select('name', 'phone')
                        ->where('id', $deal->lead_id)->first();

            $response = [
                'customer' => $deal['carrier_name'],
                'equipment' => $details['equipment_type'],
                'id' => $deal->id,
                'income' => $deal['income'],
                'lead_name' => $lead['name'],
                'lead_phone' => $lead['phone'],
                'shipment' => $details['shipment_type'],
                'pick_up_date' => $details['pick_up_date'],
                'tasks' => 0,
                'title' => $pick_up_location['city'] . ' '  . $pick_up_location['state']
                . ' - ' . $delivery_location['city'] . ' '  . $delivery_location['state'],
                'created_at' => $deal['created_at']->toDateTimeString(),
            ];

            return response()->json(['success' => true, $response]);
        } else {
            return response()->json(['success' => false], 422);
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('deal::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $deal = Deal::dealEdit($id);
        $locations = DealLocation::dealLocation($id);

        foreach ($locations as $location) {
            if ($location['type'] === self::FROM) {
                $deal['pick_up_location_tmp'] = [$location['full_location']];
            }
            if ($location['type'] === self::TO) {
                $deal['delivery_location_tmp'] = [$location['full_location']];
            }
        }

        $deal = Deal::dealResponse($deal);

        return response()->json(['success' => true, 'deal' => $deal]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $deal = $request->only(['lead_id', 'income', 'outcome', 'currency']);
        Deal::where('id', $id)->update($deal);
        $deal_details = $request->only('pick_up_date', 'delivery_date',
            'equipment_type', 'shipment_type', 'comment', 'deal_type');
        DealDetails::where('deal_id', $id)->update($deal_details);
        $deal_options = $request->only('pick_up', 'delivery', 'haz', 'tarp', 'temp');
        DealOptions::where('deal_id', $id)->update($deal_options);

        if ($request->has('pick_up_location')) {
            $pick_up_location = $this->locationParser($request->get('pick_up_location'));
            DealLocation::where('deal_id', $id)
                ->where('type', self::FROM)
                ->update($pick_up_location);
        }

        if ($request->has('delivery_location')) {
            $delivery_location = $this->locationParser($request->get('delivery_location'));
            DealLocation::where('deal_id', $id)
                ->where('type', self::TO)
                ->update($delivery_location);
        }

        $lead = Lead::select('name', 'phone')
            ->where('id', $deal['lead_id'])->first();

        $pick_up_location = DealLocation::where('deal_id', $id)
            ->where('type', self::FROM)->first()->toArray();
        $delivery_location = DealLocation::where('deal_id', $id)
            ->where('type', self::TO)->first()->toArray();

        $response = [
            'id' => $id,
            'equipment' => $deal_details['equipment_type'],
            'income' => $deal['income'],
            'lead_name' => $lead['name'],
            'lead_phone' => $lead['phone'],
            'shipment' => $deal_details['shipment_type'],
            'pick_up_date' => $deal_details['pick_up_date'],
            'tasks' => TaskModel::where('deal_id', $id)->count(),
            'title' => $pick_up_location['city'] . ' '  . $pick_up_location['state']
                . ' - ' . $delivery_location['city'] . ' '  . $delivery_location['state'],
            'created_at' => Deal::where('id', $id)->first()->created_at->toDateTimeString(),
        ];

        return response()->json(['success' => true, 'deal' => $response]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }

    public function statusUpdate(Request $request): JsonResponse
    {
        $status_id = Status::where('name', $request->get('status_name'))->first()->id;
        Deal::where('id', $request->get('deal_id'))->update(['status_id' => $status_id]);

        return response()->json(['success' => true, 'message' => $request->all()]);
    }

    public function getLeadsByContact(string $contact): JsonResponse
    {
        $leads = Lead::query()
            ->where(function ($query) use ($contact) {
                $query->where('leads.name', 'like', '%' . $contact . '%')
                    ->orWhere('email', 'like', '%' . $contact . '%')
                    ->orWhere('phone', 'like', '%' . $contact . '%');
            })->where('leads.user_id', Auth::user()->id)->get();

        if (!is_null($leads)) {
            return response()->json(['success' => true, 'leads' => $leads]);
        } else {
            return response()->json(['error' => true, 'leads' => []]);
        }
    }
}
