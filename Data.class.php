<?php

/**
 * Data
 *
 * Methods for reading/writing data.
 *
 * @author Chris Ullyott <chris@monkdevelopment.com>
 */
class Data
{
    /**
     * Read a JSON file into an array.
     */
    public static function jsonFileToArray($file)
    {
        $json = file_get_contents($file);

        return json_decode($json, true);
    }

    /**
     * Read a newline-delimited file into an array.
     */
    public static function listFileToArray($file)
    {
        $lines = trim(file_get_contents($file));

        return explode("\n", $lines);
    }

    /**
     * Read a CSV file into an array.
     */
    public static function csvFileToArray($file, $key = '')
    {
        $data = array();

        // read CSV file
        if (file_exists($file)) {
            $file = fopen($file, 'r');

            while (!feof($file)) {
                $data[] = fgetcsv($file);
            }

            fclose($file);
        } else {
            throw new Exception('File ' . basename($file) . ' not found');
        }

        // make headers
        $headers = array_shift($data);
        foreach ($headers as $k => $i) {
            $headers[$k] = strtolower(preg_replace('/\s+/', '_', $i));
        }

        // parse
        $parsedData = array();
        foreach($data as $k => $line) {

            if (!$line || !implode('', $line)) {
                continue;
            }

            $parsedItem = array();

            foreach ($headers as $k2 => $i) {
                if (!isset($line[$k2]) || strtolower($line[$k2])=='null') {
                    $parsedItem[$i] = '';
                } else {
                    $parsedItem[$i] = trim($line[$k2]);
                }
            }

            $parsedData[] = $parsedItem;
        }

        // apply keys to array (if values are unique)
        if ($key && in_array($key, $headers)) {
            $parsedDataWithKeys = array();

            foreach ($parsedData as $k => $i) {
                $keyValue = $i[$key];

                if (!isset($parsedDataWithKeys[$keyValue])) {
                    $parsedDataWithKeys[$keyValue] = $i;
                } else {
                    throw new Exception("Values of column \"{$key}\" not unique");
                }
            }

            $parsedData = $parsedDataWithKeys;
        }

        return $parsedData;
    }

    /**
     * Write a JSON file from an array.
     */
    public static function arrayToJsonFile($file, $array)
    {
        $json = json_encode($array);

        return file_put_contents($file, $json);
    }

    /**
     * Write a CSV file from an array.
     */
    public static function arrayToCsvFile($array, $filepath)
    {
        $file = fopen($filepath, 'w');

        // Get all key names
        $keys = array();

        foreach ($array as $key => $item) {
            foreach ($item as $k => $i) {
                if (!in_array($k, $keys)) {
                    $keys[] = $k;
                }
            }
        }

        $keys = array_values($keys);

        // Standardize array using all known keys
        foreach ($array as $key => $item) {
            $newItem = array();

            foreach ($keys as $k) {
                $value = !empty($item[$k]) ? $item[$k] : '';
                $newItem[$k] = $value;
            }

            $array[$key] = $newItem;
        }

        // Write headers
        fputcsv($file, $keys);

        // Write lines
        foreach ($array as $item) {
            fputcsv($file, array_values($item));
        }

        fclose($file);

        return;
    }

    /**
     * Write a CSV file from a JSON string.
     */
    public static function jsonToCsvFile($json, $filepath)
    {
        return self::arrayToCsvFile(json_decode($json, true), $filepath);
    }

    /**
     * Get a filtered array of values from a comma-separated list
     */
    public static function explodeList($csl)
    {
        $arr = explode(',', $csl);
        $arr = array_map('trim', $arr);
        $arr = array_filter($arr);

        return array_values($arr);
    }

}
