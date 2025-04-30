<?php

namespace App\Console\Commands;

use App\Models\RingCentral;
use DateTime;
use DateTimeZone;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GetCallsRecords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ring:calls-records';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get calls records from Ringcentral API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->checkAndCreateDirectory();

        $clientId = env('RINGCENTRAL_CLIENT_ID');
        $clientSecret = env('RINGCENTRAL_CLIENT_SECRET');
        $client = new Client();
        $response = $client->post('https://platform.ringcentral.com/restapi/oauth/token', [
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode("$clientId:$clientSecret"),
            ],
            'form_params' => [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => env('RINGCENTRAL_JWT'),
            ],
        ]);

        $tokenData = json_decode($response->getBody(), true);
        $accessToken = $tokenData['access_token'];

        $now = new DateTime('now', new DateTimeZone('UTC'));
        $tenMinutesAgo = (new DateTime('now', new DateTimeZone('UTC')))
            ->modify('-10 minutes');

        $dateTo = $now->format('Y-m-d\TH:i:s\Z');
        $dateFrom = $tenMinutesAgo->format('Y-m-d\TH:i:s\Z');

        try {
            $response = $client->get("https://platform.ringcentral.com/restapi/v1.0/account/~/call-log", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                ],
//                'query' => [
//                    'dateFrom' => $dateFrom, // Start time (10 minutes ago)
//                    'dateTo' => $dateTo,     // End time (now)
//                ],
            ]);
            $callLogs = json_decode($response->getBody(), true);

            $request_limit = 10;
            $request_count = 0;

            foreach ($callLogs['records'] as $call) {
                if ($request_count === $request_limit) {
                    return false;
                }
                if (isset($call['recording'])) {
                    $recordingId = $call['recording']['id'];
                    $call_exist = RingCentral::where('record_id', $recordingId)->count();

                    if (!$call_exist) {
                        $request_count++;
                        $contentUri = $call['recording']['contentUri'];
                        $recordingResponse = $client->get($contentUri, [
                            'headers' => [
                                'Authorization' => 'Bearer ' . $accessToken,
                            ],
                        ]);
                        $filename = "recording_$recordingId.mp3";

                        file_put_contents(public_path('calls_records/' . $filename),
                            $recordingResponse->getBody());

                        RingCentral::create([
                            'record_id' => $recordingId,
                            'file_path' => $filename,
                            'from' => $call['from']['name'],
                            'to' => isset($call['to']['phoneNumber']) ? $call['to']['phoneNumber'] : null,
                            'status' => $call['result'],
                            'started_at' => $call['startTime']
                        ]);
                    }
                }
            }
        } catch (Exception $e) {
            Log::error("Error: " . $e->getMessage() . "\n");
        }
    }

    private function checkAndCreateDirectory()
    {
        // Define the directory path
        $directoryName = 'calls_records';
        $directoryPath = public_path($directoryName);

        // Check if the directory exists
        if (!is_dir($directoryPath)) {
            // Create the directory
            if (mkdir($directoryPath, 0755, true)) {
                return "Directory '$directoryName' created successfully in the public folder.";
            } else {
                return "Failed to create directory '$directoryName'.";
            }
        } else {
            return "Directory '$directoryName' already exists in the public folder.";
        }
    }
}
