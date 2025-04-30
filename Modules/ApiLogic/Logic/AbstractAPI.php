<?php

namespace Modules\ApiLogic\Logic;

abstract class AbstractAPI
{
    /*TODO: Implement API Abstraction*/
    protected string $token;
    abstract public function fetchData(string $uri, string $method = 'post'): array;

}
