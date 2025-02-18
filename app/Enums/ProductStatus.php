<?php

namespace App\Enums;

enum ProductStatus
{
    case active;
    case sold;
    case not_found;
    case hold;
}
