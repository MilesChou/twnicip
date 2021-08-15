<?php

namespace Tests\Benchmark;

use MilesChou\TwnicIp\TwnicIp;
use PhpBench\Benchmark\Metadata\Annotations\Iterations;
use PhpBench\Benchmark\Metadata\Annotations\Revs;

class TwnicIpBench
{
    /**
     * @Revs(100)
     * @Iterations(5)
     */
    public function benchIsTaiwanForNiceCase()
    {
        $target = new TwnicIp();
        $target->isTaiwan('1.32.208.0');
    }

    /**
     * @Revs(100)
     * @Iterations(5)
     */
    public function benchIsTaiwanForWorstCase()
    {
        $target = new TwnicIp();
        $target->isTaiwan('223.200.51.100');
    }
}
