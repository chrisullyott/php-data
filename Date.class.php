<?php

/**
 * Date
 *
 * Methods for processing dates.
 *
 * @author Chris Ullyott <chris@monkdevelopment.com>
 */
class Date
{

    /**
     * Convert a date string into another format.
     */
    public function format($dateString, $format)
    {
        $unixTime = strtotime($dateString);

        return date($format, $unixTime);
    }

    /**
     * Find whether "overdue" based on the given due date, and a number of days given
     * to pay. Resets date strings with times into full days, to compare full days
     * regardless of time.
     */
    public static function isOverdue($dueDate, $daysGiven = 30)
    {
        $dueDate = date('Y-m-d', strtotime($dueDate));
        $overdueTime = strtotime("+$daysGiven days", strtotime($dueDate));

        return $overdueTime <= strtotime(date('Y-m-d'));
    }

    /**
     * Calculate the number of days between two dates
     */
    public static function daysBetween($date1, $date2)
    {
        $time1 = strtotime($date1);
        $time2 = strtotime($date2);
        $min   = min(array($time1, $time2));
        $max   = max(array($time1, $time2));

        return floor(($max - $min) / (60*60*24));
    }

    /**
     * Calculate the number of days between a date and today
     */
    public static function daysAgo($date)
    {
        return floor((time() - strtotime($date)) / (60 * 60 * 24));
    }

    /**
     * Determine whether a date is between two other dates
     */
    public static function isBetween($date, $start, $end)
    {
        $dateTime  = strtotime($date);
        $startTime = strtotime($start);
        $endTime   = strtotime($end);

        return ($startTime <= $dateTime) && ($dateTime <= $endTime);
    }

    /**
     * Build a friendly time difference string, like "3 days ago"
     */
    public static function timeAgo($date)
    {
        $units = array(
            'year'   => 60 * 60 * 24 * 365,
            'month'  => 60 * 60 * 24 * 30,
            'week'   => 60 * 60 * 24 * 7,
            'day'    => 60 * 60 * 24,
            'hour'   => 60 * 60,
            'minute' => 60,
            'second' => 1
        );

        $diff = time() - strtotime($date);

        if ($diff < 0) {
            return '';
        } elseif ($diff <= 5) {
            return 'just now';
        }

        foreach ($units as $n => $s) {
            if ($diff >= $s) {
                $v = floor($diff / $s);
                $text = $v == 1 ? "{$v} {$n}" : "{$v} {$n}s";
                return $text . ' ago';
            }
        }
    }

}
