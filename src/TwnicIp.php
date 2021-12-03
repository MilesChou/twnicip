<?php

declare(strict_types=1);

namespace MilesChou\TwnicIp;

use MilesChou\Ip\Collection\V4;

class TwnicIp extends V4
{
    public function __construct(bool $withoutDatabase = false)
    {
        if (!$withoutDatabase) {
            $this->addList(Database::all());
        }
    }

    public function isTaiwan(string $ip): bool
    {
        return $this->has($ip);
    }
}
