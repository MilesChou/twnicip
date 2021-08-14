<?php

namespace MilesChou\TwnicIp;

class TwnicIp
{
    public static function isTaiwan(string $ip): bool
    {
        $ipLong = ip2long($ip);

        foreach (Database::all() as [$start, $end]) {
            if ($ipLong >= $start && $ipLong <= $end) {
                return true;
            }
        }

        return false;
    }
}
