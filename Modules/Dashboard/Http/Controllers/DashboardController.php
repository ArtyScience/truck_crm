<?php

/*TODO: Refactor entire controller -> implement Service */

namespace Modules\Dashboard\Http\Controllers;

use App\Models\Status;
use App\Services\RingCentralService;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Http\Controllers\CoreController;
use Modules\Deal\Entities\Deal;
use Modules\Leads\Entities\Lead;
use Modules\Task\Entities\TaskModel;

class DashboardController extends CoreController
{
    protected RingCentralService $ringCentralService;

    public function __construct(RingCentralService $ringCentralService)
    {
        $this->ringCentralService = $ringCentralService;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $leads_week_statistic = Lead::selectRaw('
                    DATE(created_at) as day,
                    COUNT(*) as total_rows')
            ->groupBy('day')->orderBy('day', 'ASC')
            ->where('leads.user_id', Auth::user()->id)
            ->whereBetween('created_at', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek() ])->get();

        $deals_week_statistic = Deal::selectRaw('
                    DATE(created_at) as day,
                    COUNT(*) as total_rows')
            ->groupBy('day')->orderBy('day', 'ASC')
            ->where('deals.user_id', Auth::user()->id)
            ->whereBetween('created_at', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek() ])->get();

        $week_cicles = 6;
        $week_statistic_leads = array();
        $week_statistic_deals = array();
        for ($day = 0; $day <= $week_cicles; $day++) {
            if (isset($leads_week_statistic[$day])) {
                $week_statistic_leads[]
                    = $leads_week_statistic[$day]->total_rows;
            } else {
                $week_statistic_leads[] = 0;
            }

            if (isset($deals_week_statistic[$day])) {
                $week_statistic_deals[]
                    = $deals_week_statistic[$day]->total_rows;
            } else {
                $week_statistic_deals[] = 0;
            }
        }

        $data['leads_week_statistic'] = $week_statistic_leads;
        $data['deals_week_statistic'] = $week_statistic_deals;

        $statuses = Status::select('statuses.id', 'statuses.name')->get();

        foreach ($statuses as $key => $status) {
            $deals = Deal::where('status_id', $status->id)
                ->where('user_id', Auth::id())
                ->count();
            $statuses[$key]['deals'] = $deals;
        }

        $data['status_list'] = [];
        $data['deal_list'] = [];

        foreach ($statuses as $item) {
            $data['status_list'][] = $item->name;
            $data['deal_list'][] = $item->deals;
        }

        $data['tasks_list'] = TaskModel::getUserTasks(Auth::id());

        $data['tasks_list']->transform(function ($item) {

            $createdAt = $item->created_at;
            $deadline = Carbon::parse($item->deadline);

            $currentDate = Carbon::now();
            $totalDuration = $deadline->diffInSeconds($createdAt);
            $time_elapsed = $currentDate->diffInSeconds($createdAt);

            $time_remain = $totalDuration - $time_elapsed;
            $one_percent = $totalDuration / 100;

            $timeline_percent = ($totalDuration / $one_percent) - ($time_remain / $one_percent);
            $timeline = [
                'deadline' => Carbon::parse($deadline->toDateString())->format('d-m-Y'),
                'percent_elapsed' => round($timeline_percent, 2),
            ];

            $item->timeline = ($timeline['percent_elapsed'] <= 100)
                                ? $timeline['percent_elapsed'] . "%" : 100 . '%';
            $item->deadline = $timeline['deadline'];
            return $item;
        });

        $data['region_activity'] = Lead::getLeadsCountByState();

        return view('dashboard::index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('dashboard::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('dashboard::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('dashboard::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
