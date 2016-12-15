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
     * Write a full path from a list of parts (passed in as array arguments).
     *
     * @return string
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
