<?php

namespace Modules\Core\Logic\Enums;

enum RoleEnum: string
{
    case ADMIN = 'admin';
    case ACCOUNTING = 'accounting';
    case DISPATCH = 'dispatch';
    case SALES = 'sales';
    case CUSTOMMER_SUPPORT = 'customer_support';

    /**
     * @return string
     */
    public static function getValues(): array
    {
        return [
            self::ADMIN,
            self::ACCOUNTING,
            self::DISPATCH,
            self::SALES,
            self::CUSTOMMER_SUPPORT,
        ];
    }
}
