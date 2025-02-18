<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Modules\Core\Logic\JsonResponse;

class CoreController extends Controller
{
    const int ROWS = 15;

    protected object $json;
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'web']);
        $this->json = new JsonResponse();
    }

    protected function getTableRowsNumber(Request $request): int
    {
        return $request->has('rows') ? $request->get('rows') : self::ROWS;
    }
}
