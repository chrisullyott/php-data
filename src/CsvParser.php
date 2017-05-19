<?php

/**
 * Converts a CSV file into an associative array.
 *
 * @author Chris Ullyott <chris@monkdevelopment.com>
 */
class CsvParser
{
    /**
     * Parse a CSV file into an array. Creates associative array if headers are used.
     *
     * @param string $file The path to a CSV file
     * @param boolean $headerRow Whether this CSV uses a header row
     */
    public static function parse($file, $headerRow = false)
    {
        $items = array();

        $lines = self::readLines($file);
        $lines = self::sanitizeLines($lines);

        if (!$headerRow) {
            $items = $lines;
        } else {
            $headers = array_map('self::sanitizeKey', array_shift($lines));

            foreach ($lines as $k => $line) {
                foreach ($line as $k2 => $cell) {
                    $items[$k][$headers[$k2]] = $cell;
                }
            }
        }

        return $items;
    }

    /**
     * Read the lines of a CSV file with fgetcsv().
     *
     * @param string $file The path to a CSV file
     * @return array
     */
    private static function readLines($file)
    {
        $lines = array();

        if (file_exists($file)) {
            $handle = fopen($file, 'r');

            while (!feof($handle)) {
                $lines[] = fgetcsv($handle);
            }

            fclose($handle);
        } else {
            throw new Exception("File {$file} not found");
        }

        return $lines;
    }

    /**
     * Sanitize all cells of all lines of a CSV array. Sanitizes each cell and
     * removes empty rows.
     *
     * @param  array $lines An array of CSV rows.
     * @return array
     */
    private static function sanitizeLines(array $lines)
    {
        $lines = array_filter($lines);
        $lines = array_values($lines);

        foreach ($lines as $k => $line) {
            $lines[$k] = array_map('self::sanitizeCell', $lines[$k]);

            if (implode('', $lines[$k]) === '') {
                unset($lines[$k]);
            }
        }

        return $lines;
    }

    /**
     * Sanitize an individual CSV cell.
     *
     * @param  string $cell A CSV cell
     * @return string
     */
    private static function sanitizeCell($cell)
    {
        $cell = stripslashes($cell);
        $cell = trim($cell);

        if (strtolower($cell) == 'null') {
            $cell = '';
        }

        return $cell;
    }

    /**
     * Sanitize a string into a safe array key.
     *
     * @param  string $key An array key to use for the data
     * @return string
     */
    private static function sanitizeKey($key)
    {
        $key = strtolower($key);
        $key = preg_replace('/[\s-_]+/', '_', $key);
        $key = preg_replace('/[^a-z0-9_]/i', '', $key);
        $key = trim($key, '_');

        return $key;
    }

}
