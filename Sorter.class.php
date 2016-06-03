<?php

/**
 * Sorter
 *
 * Methods for sorting arrays.
 *
 * @author Chris Ullyott <chris@monkdevelopment.com>
 */
class Sorter
{
    /**
     * Sort a multi-dimensional array by a common key
     */
    public static function sortByKey(&$array, $key, $reverse = false)
    {
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

}
