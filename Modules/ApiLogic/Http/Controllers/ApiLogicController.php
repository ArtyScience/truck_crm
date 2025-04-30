<?php

namespace Modules\ApiLogic\Http\Controllers;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Modules\ApiLogic\Logic\AmadeusAPI;
use Modules\ApiLogic\Logic\DataFetcher;

class ApiLogicController extends Controller
{
    private string $secret;
    private string $key;

    public function __construct()
    {
        $this->key = 'ekBRmggNAJ3mUvnsG6IbCt18lyxnYu5f';
        $this->secret = 'hiT77SEwzNY4nVTB';
    }

    /**
     * Display a listing of the resource.
     */
    /*TODO: Implement assync REACTPHP*/
    public function index()
    {
        $query = [
            'originLocationCode' => 'LON',
            'destinationLocationCode' => 'NYC',
            'departureDate' => '2023-12-02',
            'adults' => '1',
            'nonStop' => 'false',
            'max' => '250'
        ];

        $loop = \React\EventLoop\Factory::create();

        $counter = 0;

        $loop->addPeriodicTimer(1, function () use ($counter) {
            echo ++$counter.PHP_EOL;
            //execute multiple queries
        });

        $loop->run();

        $loop->stop();

        dd('HERE');

        $amadeus = new AmadeusAPI($query);
        $data_fetcher = new DataFetcher($amadeus);

        $response = $data_fetcher->fetch(
            '/shopping/flight-offers'
        );

        dd($response);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('apilogic::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        //
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('apilogic::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('apilogic::edit');
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
