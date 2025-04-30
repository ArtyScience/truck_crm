<?php

namespace Modules\ApiLogic\Logic;

interface ContractAPI
{
    const AUTH_URL = '';
    const BASIC_URL = '';
    function getAccessToken(): string;
}
