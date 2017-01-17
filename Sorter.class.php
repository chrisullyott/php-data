<?php

/**
 * Sorter
 *
 * Methods for sorting/filtering arrays.
 *
 * @author Chris Ullyott <chris@monkdevelopment.com>
 */
class Sorter
{
    /**
     * Sort a multi-dimensional array by a common key value.
     *
     * @param  array   &$array   The array to sort (passed by reference)
     * @param  string  $key      The key to sort by
     * @param  boolean $reverse  Whether to sort in descending order
     * @return array
     */
    private static function sortByKey(&$array, $key, $reverse = false)
    {
        if (!$array) {
            return;
        }

        $sorter = array();
        $ret = array();
        reset($array);

        foreach ($array as $i => $va) {
            $sorter[$i] = $va[$key];
        }

        asort($sorter);

        foreach ($sorter as $i => $va) {
            $ret[$i] = $array[$i];
        }

        if ($reverse) {
            $ret = array_reverse($ret);
        }

        $ret = array_values($ret);

        $array = $ret;
    }

    /**
     * Filter an array where a key is equal to a value
     */
    public static function filterByKey($array, $key, $value)
    {
        $filteredArray = array();

        foreach ($array as $k => $i) {
            if (isset($i[$key]) && ($i[$key] == $value)) {
                $filteredArray[] = $i;
            }
        }

        return $filteredArray;
    }

}
