<?php

/**
 * File
 *
 * Methods for reading/writing files.
 *
 * @author Chris Ullyott <chris@monkdevelopment.com>
 */
class File
{
    /**
     * Load and decode JSON data.
     */
    public static function jsonFileToArray($file)
    {
        $json = file_get_contents($file);

        return json_decode($json, true);
    }

    /**
     * Write a full path from a list of parts
     */
    public static function path()
    {
        $path = '';

        foreach (func_get_args() as $key => $p) {
            if ($key == 0) {
                $path .= rtrim($p, '/') . '/';
            } else {
                $path .= trim($p, '/') . '/';
            }
        }

        return rtrim($path, '/');
    }

}
