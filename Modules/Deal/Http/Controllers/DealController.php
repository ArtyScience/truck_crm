<?php

/*TODO: Make entire controller refactoring, implement Desing Patterns!!!*/

namespace Modules\Deal\Http\Controllers;

use App\Http\Requests\DealCreateRequest;
use App\Models\Status;
use DateTime;
use Illuminate\Http\JsonResponse;
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
use Modules\Deal\Services\DealService;
use Modules\Leads\Entities\Lead;
use Modules\Task\Entities\TaskModel;

class DealController extends CoreController
{
    use LocationTrait;

    public function __construct(
        protected DealService $service,
        protected DealRepository $repository,
        protected Deal $model = new Deal()
    ) {
        $this->repository->setModel($this->model);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): View|JsonResponse
    {
       $data['deals'] = $this->service->index();
        return view('deal::index', compact('data'));
    }

    public function all(): JsonResponse
    {
        $response = Deal::select('id as value', 'name as label')
                    ->where('user_id', Auth::id())->get();

        return response()->json($response);
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
