<?php

namespace Tests\Unit;

use MilesChou\TwnicIp\TwnicIp;
use Tests\TestCase;

class TwnicIpTest extends TestCase
{
    /**
     * @var TwnicIp
     */
    private $target;

    protected function setUp(): void
    {
        $this->target = new TwnicIp();
    }

    /**
     * @test
     */
    public function shouldReturnTrueWhenTaiwanIp(): void
    {
        // ['3391586304', '3391619071', '202.39.128.0', '202.39.255.255', '中華電信數據分公司(HiNet)'],

        $this->assertTrue($this->target->isTaiwan('202.39.145.2'));
    }

    /**
     * @test
     */
    public function shouldReturnFalseWhenTaiwanIp(): void
    {
        $this->assertFalse($this->target->isTaiwan('127.0.0.1'));
    }

    /**
     * @test
     */
    public function shouldReturnFalseWhenNotTaiwanLong(): void
    {
        // 2130706433 means 127.0.0.1
        $this->assertFalse($this->target->isTaiwanByLong(2130706433));
    }

    /**
     * @test
     */
    public function shouldReturnTrueWhenIpInCustomizeRange(): void
    {
        $this->target->includeRange('127.0.0.1', '127.0.0.1');

        $this->assertTrue($this->target->isTaiwan('127.0.0.1'));
    }

    /**
     * @test
     */
    public function shouldReturnTrueWhenLongInCustomizeRange(): void
    {
        // 2130706433 means 127.0.0.1
        $this->target->includeRangeByLong(2130706433, 2130706433);

        $this->assertTrue($this->target->isTaiwan('127.0.0.1'));
    }

    /**
     * @test
     */
    public function shouldReturnFalseWhenIpInExcludeRange(): void
    {
        // ['3391586304', '3391619071', '202.39.128.0', '202.39.255.255', '中華電信數據分公司(HiNet)'],
        // Before exclude will return true
        $this->assertTrue($this->target->isTaiwan('202.39.128.2'));

        // Act
        $this->target->excludeRange('202.39.128.1', '202.39.128.3');

        // After exclude will return false
        $this->assertFalse($this->target->isTaiwan('202.39.128.1'));
        $this->assertFalse($this->target->isTaiwan('202.39.128.2'));
        $this->assertFalse($this->target->isTaiwan('202.39.128.3'));
    }

    /**
     * @test
     */
    public function shouldReturnFalseWhenLongInExcludeRange(): void
    {
        // ['3391586304', '3391619071', '202.39.128.0', '202.39.255.255', '中華電信數據分公司(HiNet)'],
        // Before exclude will return true
        $this->assertTrue($this->target->isTaiwanByLong(3391586306));

        // Act
        $this->target->excludeRangeByLong(3391586305, 3391586307);

        // After exclude will return false
        $this->assertFalse($this->target->isTaiwanByLong(3391586305));
        $this->assertFalse($this->target->isTaiwanByLong(3391586306));
        $this->assertFalse($this->target->isTaiwanByLong(3391586307));
    }


    /**
     * @test
     */
    public function excludeShouldOverwriteConfig(): void
    {
        // 2130706433 means 127.0.0.1
        $this->target->includeRangeByLong(2130706433, 2130706433);

        $this->assertTrue($this->target->isTaiwan('127.0.0.1'));
    }
}
