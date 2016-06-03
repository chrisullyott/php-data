<?php

/**
 * Percent
 *
 * Methods for calculating percentages.
 *
 * @author Chris Ullyott <chris@monkdevelopment.com>
 */
class Percent
{
  /**
   * Calculate percentage
   */
  public static function getPercent($n, $total, $suffix = '%')
  {
    $percent = ($n / $total) * 100;
    $percent = number_format((float)$percent, 2, '.', '');
    
    return $percent . $suffix;
  }
  
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
        $output[$i]['percent'] = self::getPercent($count, count($array));
    }
    
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
