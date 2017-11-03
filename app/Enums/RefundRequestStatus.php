<?php

namespace App\Enums;

class RefundRequestStatus
{
    const PENDING = 0;
    const RESOLVED = 1;
    const DECLINED = 2;
    const DELETED = 3;

    public static function to_string($status)
    {
        switch ($status) {
            case self::PENDING: return 'pending';
            case self::RESOLVED: return 'resolved';
            case self::DECLINED: return 'declined';
            case self::DELETED: return 'deleted';
        }
    }

    public static function from_string($status_string)
    {
        switch ($status_string) {
            case 'pending': return self::PENDING;
            case 'resolved': return self::RESOLVED;
            case 'declined': return self::DECLINED;
            case 'deleted': return self::DELETED;
        }
    }
}
