<?php

namespace Modules\Elearning\Support;

class UploadExpire
{
    /**
     * Calculate the expire time for presigned upload.
     *
     * @param int      $sizeBytes The size of the file in bytes
     * @param float    $mbps      The upload speed in megabits per second
     * @param float    $overhead  The overhead factor (default 1.3)
     * @param int      $buffer    The buffer time in seconds (default 600s = 10 minutes)
     * @param int|null $min       The minimum expire time in seconds (default 900s)
     * @param int|null $max       The maximum expire time in seconds (default 14400s = 4 hours)
     */
    public static function for(int $sizeBytes, float $mbps = 20.0, float $overhead = 1.3, int $buffer = 600, ?int $min = 900, ?int $max = 14400): int
    {
        // size_bytes * 8 / (mbps * 1e6) -> seconds
        $t = ($sizeBytes * 8) / ($mbps * 1_000_000);
        $estimate = (int) ceil($t * $overhead) + $buffer;
        if ($min !== null) $estimate = max($estimate, $min);
        if ($max !== null) $estimate = min($estimate, $max);
        return $estimate;
    }
}
