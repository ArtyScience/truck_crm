<?php

namespace Modules\Task\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TaskModel extends Model
{
    use HasFactory;

    protected $table = 'tasks';
    protected $fillable = ['title', 'description', 'user_id', 'lead_id',
                           'deal_id', 'status', 'deadline', 'priority'];

    const int ACTIVE = 1;
    const int INACTIVE = 0;

    const int HIGHT_PRIORITY = 1;

    private static array $task_fields = [
        'tasks.id', 'title', 'description',
        'deals.id as deal_id', 'leads.name as lead_name',
        'users.name as user_name', 'tasks.status',
        'tasks.priority', 'tasks.deadline'
    ];

    public static function getTasks()
    {
        return self::select(self::$task_fields)
            ->leftJoin('users', 'users.id', '=', 'tasks.user_id')
            ->leftJoin('leads', 'leads.id', '=', 'tasks.lead_id')
            ->leftJoin('deals', 'deals.id', '=', 'tasks.deal_id')
            ->orderBy('tasks.id', 'desc')
            ->where('tasks.user_id', Auth::id())
            ->distinct()->orderBy('tasks.id', 'DESC')->paginate(25);
    }

    public static function getTask(int $task_id = null)
    {
        $query = self::select(self::$task_fields)
            ->leftJoin('users', 'users.id', '=', 'tasks.user_id')
            ->leftJoin('leads', 'leads.id', '=', 'tasks.lead_id')
            ->leftJoin('deals', 'deals.id', '=', 'tasks.deal_id');

        if (!empty($task_id)) $query->where('tasks.id', $task_id);

        return $query->orderBy('tasks.id', 'desc')->distinct()->get();
    }

    public static function getUserTasks(int $user_id)
    {
        return self::select('tasks.id', 'tasks.title', 'tasks.deadline',
                            'tasks.priority', 'tasks.created_at', 'tasks.status')
            ->where('user_id', $user_id)->where('tasks.status', TaskModel::ACTIVE)
            ->get();
    }

    /**
     * @param $tasks
     * @return mixed
     */
    public static function parseResponse($tasks)
    {
        $tasks->transform(function ($item) {

            $createdAt = $item->created_at;
            $deadline = Carbon::parse($item->deadline);

            $currentDate = Carbon::now();
            $totalDuration = $deadline->diffInSeconds($createdAt);
            $time_elapsed = $currentDate->diffInSeconds($createdAt);

            $time_remain = $totalDuration - $time_elapsed;
            $one_percent = $totalDuration / 100;

            $timeline_percent = ($totalDuration / $one_percent)
                - ($time_remain / $one_percent);
            $timeline = [
                'deadline' => Carbon::parse($deadline->toDateString())->format('d-m-Y'),
                'percent_elapsed' => round($timeline_percent, 2),
            ];

            $item->timeline = ($timeline['percent_elapsed'] <= 100)
                ? $timeline['percent_elapsed'] . "%" : 100 . '%';
            $item->deadline = $timeline['deadline'];
            return $item;
        });

        return $tasks;
    }
}
