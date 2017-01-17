<?php

/**
 * Math
 *
 * Methods for doing arithmetic operations.
 *
 * @author Chris Ullyott <chris@monkdevelopment.com>
 */
class Math
{
    public function mean($arr)
    {
        if (!is_array($arr) || empty($arr)) {
            return false;
        }

        return array_sum($arr) / count($arr);
    }

    public function median($arr)
    {
        if (!is_array($arr) || empty($arr)) {
            return false;
        }

        $count = count($arr);

        $mid = floor(($count-1) / 2);

        if ($count % 2) {
            $median = $arr[$mid];
        } else {
            $median = self::mean(array($arr[$mid], $arr[$mid+1]));
        }

        return $median;
    }

    public static function percent($n, $total, $suffix = '%')
    {
        $percent = ($n / $total) * 100;
        $percent = number_format((float) $percent, 2, '.', '');

        return $percent . $suffix;
    }

}
