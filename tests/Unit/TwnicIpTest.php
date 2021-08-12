<?php

namespace Tests\Unit;

use MilesChou\TwnicIp\TwnicIp;
use Tests\TestCase;

class TwnicIpTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturnTrueWhenTaiwanIp(): void
    {
        // ['3391586304', '3391619071', '202.39.128.0', '202.39.255.255', '中華電信數據分公司(HiNet)'],

        $this->assertTrue(TwnicIp::isTaiwan('202.39.145.2'));
    }

    /**
     * @test
     */
    public function shouldReturnFalseWhenTaiwanIp(): void
    {
        $this->assertFalse(TwnicIp::isTaiwan('127.0.0.1'));
    }
}
