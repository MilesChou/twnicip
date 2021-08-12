<?php

namespace Tests\Unit;

use MilesChou\TwnicIp\TwnicIp;
use Tests\TestCase;

class TwnicIpTest extends TestCase
{
    /**
     * @test
     */
    public function sample(): void
    {
        $this->assertTrue((new TwnicIp())->alwaysTrue());
    }
}
