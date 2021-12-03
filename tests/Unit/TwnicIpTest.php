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
    public function shouldReturnFalseWhenWithoutDatabase(): void
    {
        $target = new TwnicIp(true);

        $this->assertFalse($target->isTaiwan('202.39.145.2'));
    }

    /**
     * @test
     */
    public function shouldReturnTrueWhenTaiwanIp(): void
    {
        // 202.39.145.2 中華電信數據分公司(HiNet)
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
    public function shouldReturnTrueWhenLongInCustomizeRange(): void
    {
        $this->target->addLoopbackIp();

        $this->assertTrue($this->target->isTaiwan('127.0.0.1'));
    }

    /**
     * @test
     */
    public function excludeShouldOverwriteConfig(): void
    {
        $this->target->add(2130706433, 2130706433);

        $this->assertTrue($this->target->isTaiwan('127.0.0.1'));
    }
}
