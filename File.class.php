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

    /**
     * List files in a directory, ordered by their modified time. "ASC" means
     * "ascending", or, listed by the oldest first.
     */
    public static function listFiles($dir, $pattern = '*', $order = 'asc')
    {
        $files = array();
        $globPath = rtrim($dir, '/') . '/' . $pattern;
        $fileGlob = glob($globPath);

        // Get all files
        foreach ($fileGlob as $path) {
            if (is_file($path)) {
                $files[] = array(
                    'path' => $path,
                    'time' => filemtime($path)
                );
            }
        }

        // Sort files by date
        $times = array();

        foreach ($files as $key => $file) {
            $times[$key] = $file['time'];
        }

        if ($order == 'asc') {
            array_multisort($times, SORT_ASC, $files);
        } elseif ($order == 'desc') {
            array_multisort($times, SORT_DESC, $files);
        }

        return $files;
    }

    /**
     * Paired with listFiles(), this method deletes the oldest files from a directory
     * and leaves the latest ones alone.
     */
    public static function deleteFilesExceptLatest($dir)
    {
        $files = self::listFiles($dir);

        // Select all files except the latest ten
        $files = array_slice($files, 0, (count($files) - 10));

        // Delete all
        foreach ($files as $file) {
            if (unlink($file['path'])) {
                echo "Deleted: {$file['path']}\n";
            }
        }
    }

}
