<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use RC\SDK;

class RingCentralService
{
    protected $sdk;

    public function __construct()
    {
        $this->sdk = new SDK(
            env('RINGCENTRAL_CLIENT_ID'),
            env('RINGCENTRAL_CLIENT_SECRET'),
            env('RINGCENTRAL_SERVER_URL')
        );
    }

    public function authenticate()
    {
        $platform = $this->sdk->getPlatform();

        try {
            $data_object = new \stdClass();
            $data_object->access_token = 'eyJraWQiOiI4NzYyZjU5OGQwNTk0NGRiODZiZjVjYTk3ODA0NzYwOCIsInR5cCI6IkpXVCIsImFsZyI6IlJTMjU2In0.eyJhdWQiOiJodHRwczovL3BsYXRmb3JtLnJpbmdjZW50cmFsLmNvbS9yZXN0YXBpL29hdXRoL3Rva2VuIiwic3ViIjoiNDQyNTc4MDI5IiwiaXNzIjoiaHR0cHM6Ly9wbGF0Zm9ybS5yaW5nY2VudHJhbC5jb20iLCJleHAiOjM4ODczMDAwOTMsImlhdCI6MTczOTgxNjQ0NiwianRpIjoieUhoYk9wT2pSQWU5Z0ZwLUdsS2FQdyJ9.M4lOMYdARjtuMK4-VQRAyi7gAncxZKrD6-8e554Li8IPe6AHx7khTlKa13wO4u1dscxxUK_H2kOLjE-eM053u0DgHoRFs9wZHcWZUkf0nYgV1ohQ_7wu7xI2IW0XxG2jMw_QZs11Kj6LvFiIwkLVEfBphgD7bVRqYB2P5UJhAO6mMQUkhZbSur7CMXyW2dpLfEy4Ep1lluixdV9SCnd-folzoald3wHzUdMo9gNp1aqHacZY93w1S265V0SLdAkdWwkr62TNM_zFwzWte2ndmnaGzxHK96nfBac6JslDRXXhIdk5UsSE3F0nLEB4-TeVnfdY8MQh0odcA44zkPbBww';

            //$platform->setAuthData($data_object);


            if (!$platform->isAuthorized()) {
                $platform->authorize([
                    'jwt' => $data_object->access_token
                ]);
            }

            dd($platform);

            $platform->loggedIn();

            $platform->authorize(
                env('RINGCENTRAL_USERNAME'),
                env('RINGCENTRAL_EXTENSION'),
                env('RINGCENTRAL_PASSWORD'));



        } catch (\Exception $e) {
            //dd($platform);

            dd($e->getCode(), $e->getMessage());
            Log::error('RingCentral Authentication Error: ' . $e->getMessage());
            return false;
        }

        return $platform;
    }

    public function getCallLogs()
    {
        $platform = $this->authenticate();

        if (!$platform) {
            return null;
        }

        try {
            $response = $platform->get('/account/~/call-log', [
                'dateFrom' => now()->subDays(30)->toIso8601String(), // Last 7 days
                'perPage' => 100
            ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Error fetching RingCentral call logs: ' . $e->getMessage());
            return null;
        }
    }
}
