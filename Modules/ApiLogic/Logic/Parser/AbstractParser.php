<?php

namespace ApiLogic\Logic\Parser;

abstract class AbstractParser
{
    abstract function parse(array $data): array;
}
