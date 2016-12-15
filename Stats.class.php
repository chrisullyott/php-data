<?php

/**
 * Stats
 *
 * Methods for calculating percentages.
 *
 * @author Chris Ullyott <chris@monkdevelopment.com>
 */
class Stats
{
    /**
     * Derive percentage of occurrences of array items
     */
    public static function arrayOccurrenceStats($array, $print = false)
    {
        $output = array();

        // Make an array with the unique items
        $uniqueItems = array_unique($array);
        foreach ($uniqueItems as $i) {
            $output[$i] = array();
        }

        // Get the percentage occurrances of each item
        foreach ($uniqueItems as $i) {
            $count = 0;

            foreach ($array as $ii) {
                if ($ii === $i) {
                    $count++;
                }
            }

            $output[$i]['count'] = $count;
            $output[$i]['percent'] = Math::percent($count, count($array));
        }

        Sorter::sortByKey($output, 'count', true);

        if ($print) {
            foreach($output as $k => $i) {
                echo $k . "\n";

                foreach ($i as $kk => $ii) {
                    echo $kk . "\t\t" . $ii . "\n";
                }
            }
        } else {
            return $output;
        }
    }

}
