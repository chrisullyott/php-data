<?php

/**
 * Data
 *
 * Methods for loading/parsing data.
 *
 * @author Chris Ullyott <chris@monkdevelopment.com>
 */
class Data
{
    /**
     * Create a CSV file from an array of data.
     */
    public static function arrayToCSV($array, $filepath)
    {
        $file = fopen($filepath, 'w');

        foreach ($array as $key => $fields) {
            if ($key == 0) {
                $headers = array();

                foreach ($fields as $name => $field) {
                    $headers[] = $name;
                }

                fputcsv($file, $headers);
            }

            fputcsv($file, $fields);
        }

        fclose($file);

        return;
    }

    /**
     * Parse a CSV file into an array
     */
    public static function parseCSV($file, $key = '')
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
                    throw new Exception('Values under header ' . $key . ' not unique');
                }
            }

            $parsedData = $parsedDataWithKeys;
        }

        return $parsedData;
    }

}
