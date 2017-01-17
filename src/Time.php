<?php

/**
 * Time
 *
 * Methods for working with time.
 *
 * @author Chris Ullyott <chris@monkdevelopment.com>
 */
class Time 
{
    /**
     * Get the time elapsed (in milliseconds).
     * 
     * @param  float $startTime The start time
     * @param  float $endTime   The end time
     * @return float            
     */
    public static function elapsed($startTime, $endTime = null)
    {
        if (!$endTime) {
            $endTime = microtime(true);
        }

        return round(($endTime - $startTime), 4) . 's';
    }
    
}
