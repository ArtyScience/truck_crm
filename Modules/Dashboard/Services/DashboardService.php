<?php

namespace Modules\Dashboard\Services;

use App\Models\RingCentral;
use App\Models\Status;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\Deal\Entities\Deal;
use Modules\Leads\Entities\Lead;
use Modules\Task\Entities\TaskModel;

class DashboardService
{
    public function index()
    {
        $data['leads_week_statistic'] = $this->parseWeekStatistics(Lead::getLeadsStatistics());
        $data['deals_week_statistic'] = $this->parseWeekStatistics(Deal::getDealStatistics());

        $statuses = Status::select('statuses.id', 'statuses.name')->get();

        foreach ($statuses as $key => $status) {
            $deals = Deal::getStatusesCount($status->id);
            $statuses[$key]['deals'] = $deals;
        }

        $data['status_list'] = [];
        $data['deal_list'] = [];

        foreach ($statuses as $item) {
            $data['status_list'][] = $item->name;
            $data['deal_list'][] = $item->deals;
        }

        $tasks = TaskModel::getUserTasks(Auth::id());
        $data['tasks_list'] = TaskModel::parseResponse($tasks);

        $data['region_activity'] = Lead::getLeadsCountByState();
        $data['call_logs'] = RingCentral::all();
        $data['role'] = User::getUserRole();

        return view('dashboard::index', compact('data'));
    }

    private function parseWeekStatistics($statistics)
    {
        $week_cicles = 7;
        $week_statistics = array();
        for ($day = 1; $day <= $week_cicles; $day++) {
            if ($statistics->first()->day == $day) {
                $week_statistics[] = $statistics->first()->total_rows;
            } else {
                $week_statistics[] = 0;
            }
        }

        return $week_statistics;
    }
}