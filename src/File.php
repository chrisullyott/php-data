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
     * Copy a given file to another directory.
     */
    public static function copy($file, $directory)
    {
        $basename = basename($file);
        $contents = file_get_contents($file);

        $path = rtrim($directory, '/') . '/' . $basename;

        return file_put_contents($path, $contents);
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
     * Recursively list all files in a directory of a given filetype.
     *
     * @param  string $directory The path to the directory
     * @param  string $extension A given file extension to filter by
     * @return array
     */
    private static function listDirectory($directory, $extension = 'php')
    {
        $dir = new RecursiveDirectoryIterator($directory);
        $ite = new RecursiveIteratorIterator($dir);
        $pattern = "/.*\.{$extension}$/";

        $files = new RegexIterator($ite, $pattern, RegexIterator::GET_MATCH);

        $fileList = array();

        foreach ($files as $file) {
            $fileList = array_merge($fileList, $file);
        }

        return $fileList;
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

    public static function formatBytes($bytes, $precision = 0)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

}
