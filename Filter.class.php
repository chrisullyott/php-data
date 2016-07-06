<?php

/**
 * Filter
 *
 * Methods for filtering arrays.
 *
 * @author Chris Ullyott <chris@monkdevelopment.com>
 */
class Filter
{
    /**
     * Filter an array where a key is equal to a value
     */
    public static function filterArray($array, $key, $value) {
        $filteredArray = array();

        foreach ($array as $k => $i) {
            if (isset($i[$key]) && ($i[$key] == $value)) {
                $filteredArray[] = $i;
            }
        }

        return $filteredArray;
    }
    
}
