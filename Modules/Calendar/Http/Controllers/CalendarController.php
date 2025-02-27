<?php

namespace Modules\Calendar\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Modules\Core\Http\Controllers\CoreController;

class CalendarController extends CoreController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('calendar::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('calendar::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->except('_options');

        $response = Http::withHeaders([
            'Authorization' =>
                'Bearer 903BBF803984F10916D8B8F12432D0A9B9E16569BEB1FFFDC581DE30794DCB6B',
            'Content-Type' => 'application/json'
        ])->post('https://ef60a524-6a00-473a-829f-09beb7500203.pushnotifications.pusher.com/publish_api/v1/instances/ef60a524-6a00-473a-829f-09beb7500203/publishes', [
            "interests" => ["calendar_notification"],
            "web" => ["notification" =>
                [
                    "title" => "Calendar reminder",
                    "body" => $data['title'] . ' at:  ' . $data['start']
                ]
            ]
        ]);

        return response()->json($response);
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('calendar::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('calendar::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
