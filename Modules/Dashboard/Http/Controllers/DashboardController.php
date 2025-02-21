<?php

namespace Modules\Dashboard\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Services\RingCentralService;
use Illuminate\Contracts\Support\Renderable;
use Modules\Dashboard\Services\DashboardService;
use Modules\Core\Http\Controllers\CoreController;

class DashboardController extends CoreController
{
    protected RingCentralService $ringCentralService;
    protected DashboardService $dashboardService;

    public function __construct(
        RingCentralService $ringCentralService,
        DashboardService $dashboardService
    )
    {
        $this->ringCentralService = $ringCentralService;
        $this->dashboardService = $dashboardService;
    }

    public function index(): View
    {
        return $this->dashboardService->index();
    }

    public function create(): View
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
