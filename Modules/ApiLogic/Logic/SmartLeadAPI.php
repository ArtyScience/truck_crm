<?php

namespace Modules\ApiLogic\Logic;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use http\Encoding\Stream\Inflate;

class SmartLeadAPI extends AbstractAPI implements ContractAPI
{
    //INFO: API KEY is temporary should be changed in PROD
    protected string $apiKey = "a53a3d23-16a8-4cea-bb5e-66f6ca9cda86_818db70";
    protected string $url = "https://server.smartlead.ai/api/v1";
    protected mixed $data = [];
    protected string $token;
    protected Client $client;
    protected string $request_settings;

    public function __construct(mixed $data = [])
    {
        $this->data = $data;
        $this->client = new Client();
        $this->setRequestSettings('');
    }

    public function fetchData(string $uri, string $method = 'post'): array
    {
        if ($method === 'post') {
            $response = $this->client->post($this->url . $uri, [
                'query' => ['api_key' => $this->apiKey],
                'json' => $this->data,
            ]);
        } else if ($method === 'get') {
            $get_request = $this->url . $uri . '?api_key='
                . $this->apiKey . $this->request_settings;
            $response = $this->client->get($get_request);
        } else if ($method === 'delete') {
            $response = $this->client->delete($this->url . $uri . '?api_key=' . $this->apiKey);
        } else {
            throw new \Exception("Method not supported");
        }

        return json_decode($response->getBody()->getContents(), true);
    }

    public function getAccessToken(): string
    {
        return $this->token;
    }

    public function setRequestSettings(string $settings)
    {
        $this->request_settings = $settings;
    }
}
