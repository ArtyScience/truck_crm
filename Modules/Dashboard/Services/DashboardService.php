<?php

namespace Modules\Dashboard\Services;

use App\Models\Status;
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

    private function parseWeekStatistics($statistics)
    {
        $week_cicles = 6;
        $week_statistics = array();
        for ($day = 0; $day <= $week_cicles; $day++) {
            if (isset($statistics[$day])) {
                $week_statistics[] = $statistics[$day]->total_rows;
            } else {
                $week_statistics[] = 0;
            }
        }

        return $week_statistics;
    }
}