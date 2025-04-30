<?php

namespace Modules\ApiLogic\Logic;

class DataFetcher
{
    private AbstractAPI $apiService;
    protected array $data;

    public function __construct(AbstractAPI $apiService)
    {
        $this->apiService = $apiService;
    }

    public function fetch(string $url): self
    {
        $this->data = $this->apiService->fetchData($url);
        return $this;
    }

    public function processFlights() {

    }
}
