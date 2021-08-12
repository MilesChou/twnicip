<?php

namespace MilesChou\TwnicIp;

class TwnicIp
{
    use Database;

    public static function isTaiwan(string $ip): bool
    {
        $ipLong = ip2long($ip);

        foreach (self::$raw as [$start, $end]) {
            if ($ipLong >= $start && $ipLong <= $end) {
                return true;
            }
        }

        return false;
    }
}
