<?php

namespace MilesChou\TwnicIp;

class TwnicIp
{
    /**
     * @var array Extra Taiwan IP range
     */
    private $include = [];

    /**
     * @var array Exclude the non-Taiwan IP range
     */
    private $exclude = [];

    /**
     * Build ip range data by IP
     */
    public static function buildRangeByIp(string $start, string $end, string $title): array
    {
        return [ip2long($start), ip2long($end), $start, $end, $title];
    }

    /**
     * Build ip range data by Long
     */
    public static function buildRangeByLong(int $start, int $end, string $title): array
    {
        return [$start, $end, long2ip($start), long2ip($end), $title];
    }

    /**
     * Alias for isTaiwanByIp() method
     */
    public function isTaiwan(string $ip): bool
    {
        return $this->isTaiwanByIp($ip);
    }

    public function isTaiwanByIp(string $ip): bool
    {
        return $this->isTaiwanByLong(ip2long($ip));
    }

    public function isTaiwanByLong(int $ip): bool
    {
        $result = false;

        // Check default database from https://www.twnic.tw
        foreach (Database::all() as [$start, $end]) {
            if ($ip >= $start && $ip <= $end) {
                $result = true;
                break;
            }
        }

        // Check list to include
        foreach ($this->include as [$start, $end]) {
            if ($ip >= $start && $ip <= $end) {
                $result = true;
                break;
            }
        }

        // Check list to exclude
        foreach ($this->exclude as [$start, $end]) {
            if ($ip >= $start && $ip <= $end) {
                $result = false;
                break;
            }
        }

        return $result;
    }

    /**
     * Alias for includeRangeByIp()
     */
    public function includeRange(string $start, string $end, string $title = ''): void
    {
        $this->includeRangeByIp($start, $end, $title);
    }

    /**
     * Use string to mark the Taiwan IP range
     */
    public function includeRangeByIp(string $start, string $end, string $title = ''): void
    {
        $this->include[] = self::buildRangeByIp($start, $end, $title);
    }

    /**
     * Use long int to mark the Taiwan IP range
     */
    public function includeRangeByLong(int $start, int $end, string $title = ''): void
    {
        $this->include[] = self::buildRangeByLong($start, $end, $title);
    }

    /**
     * Alias for excludeRangeByIp()
     */
    public function excludeRange(string $start, string $end, string $title = ''): void
    {
        $this->excludeRangeByIp($start, $end, $title);
    }

    /**
     * Use string to mark the non-Taiwan IP range
     */
    public function excludeRangeByIp(string $start, string $end, string $title = ''): void
    {
        $this->exclude[] = self::buildRangeByIp($start, $end, $title);
    }

    /**
     * Use long int to mark the non-Taiwan IP range
     */
    public function excludeRangeByLong(int $start, int $end, string $title = ''): void
    {
        $this->exclude[] = self::buildRangeByLong($start, $end, $title);
    }

    /**
     * Clean all customize ip range
     */
    public function clean()
    {
        $this->cleanInclude();
        $this->cleanExclude();
    }

    /**
     * Clean customize include ip range
     */
    public function cleanInclude()
    {
        $this->include = [];
    }

    /**
     * Clean customize exclude ip range
     */
    public function cleanExclude()
    {
        $this->exclude = [];
    }
}
