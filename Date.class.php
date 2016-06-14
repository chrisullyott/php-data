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
     * Find whether "overdue" based on the given due date, and a number of days given
     * to pay. Resets date strings with times into full days, to compare full days
     * regardless of time.
     */
    public static function isOverdue($dueDate, $daysGiven = 30) {
        $dueDate = date('Y-m-d', strtotime($dueDate));
        $overdueTime = strtotime("+$daysGiven days", strtotime($dueDate));

        return $overdueTime <= strtotime(date('Y-m-d'));
    }

    /**
     * Calculate the number of days between two dates
     */
    public static function daysBetween($date1, $date2) {
        $time1 = strtotime($date1);
        $time2 = strtotime($date2);
        $min   = min(array($time1, $time2));
        $max   = max(array($time1, $time2));

        return floor(($max - $min) / (60*60*24));
    }

    /**
     * Calculate the number of days between a date and today
     */
    public static function daysAgo($date) {
        return floor((time() - strtotime($date)) / (60*60*24));
    }
}
