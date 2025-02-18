<?php

namespace Modules\Task\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Http\Controllers\CoreController;
use Modules\Task\Entities\TaskModel;
use Modules\Task\Http\Requests\TaskStoreRequest;
use Modules\Task\Http\Requests\TaskCreateRequest;

class TaskController extends CoreController
{
    private array $priorities = [
        0 => 'Normal',
        1 => 'Medium',
        2 => 'High',
    ];

    private array $statuses = [
        0 => 'Inactive',
        1 => 'Active',
    ];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tasks = $this->collectionParser(TaskModel::getTasks());

        $data['tasks'] = response()->json($tasks)->content();

        if ($request->get('page')) return response()->json($tasks);

        return view('task::index', compact('data'));
    }

    public function taskDone(Request $request): JsonResponse
    {
        TaskModel::where('id', $request->id)->update(['status' => TaskModel::INACTIVE]);
        return response()->json(['message' => 'Task done']);
    }



    private function collectionParser(LengthAwarePaginator $collection): LengthAwarePaginator
    {
        $collection->getCollection()->transform(function ($item) {

            $item->status = $item->status ? 'Active' : 'Inactive';
            $item->priority = $this->priorities[$item->priority];

            return $item;
        });

        return $collection;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('task::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskStoreRequest $request): JsonResponse
    {
        $data = $request->all();
        $data['deadline'] = Carbon::parse($data['deadline']);
        $data['user_id'] = Auth::id();
        $created_task = TaskModel::create($data);
        $task = TaskModel::getTask($created_task->id);

        $task = $task->transform(function ($item) {
            $item->status = $item->status ? 'Active' : 'Inactive';
            $item->priority = $this->priorities[$item->priority];
            return $item;
        });

        return response()->json([
            'message' => 'Task created successfully.',
            'task' => $task->first()]
        );
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('task::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): JsonResponse
    {
        $task_fields = [
            'tasks.id',
            'title',
            'description',
            'deals.id as deal_id',
            'leads.id as lead_id',
            'users.id as user_id',
            'tasks.status',
            'leads.name as lead_name',
            'users.name as user_name',
            'tasks.priority',
            'tasks.deadline'
        ];

        $task = TaskModel::select($task_fields)
            ->leftJoin('users', 'users.id', '=', 'tasks.user_id')
            ->leftJoin('leads', 'leads.id', '=', 'tasks.lead_id')
            ->leftJoin('deals', 'deals.id', '=', 'tasks.deal_id')
            ->where('tasks.id', $id)
            ->first();

        return response()->json($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskStoreRequest $request, $id): JsonResponse
    {
        $task_update = $request->only('title', 'description', 'deadline',
            'user_id', 'deal_id', 'lead_id', 'priority');
        TaskModel::where('id', $id)->update($task_update);

        $task = TaskModel::getTask($id)->first();
        $task->priority = $this->priorities[$task->priority];
        $task->status = $this->statuses[$task->status];

        return response()->json(['message' => 'Task updated successfully.', 'task' => $task]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        TaskModel::destroy($id);
        return response()->json('Task deleted successfully');
    }
}
