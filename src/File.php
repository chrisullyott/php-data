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
     * Build a full path from parts passed as arguments.
     *
     * @return string
     */
    public static function path()
    {
        $parts = func_get_args();

        $sep = DIRECTORY_SEPARATOR;

        $path = rtrim(array_shift($parts), $sep) . $sep;

        foreach ($parts as $p) {
            $path .= trim($p, $sep) . $sep;
        }

        return trim($path, $sep);
    }

    /**
     * Create a directory if it doesn't exist.
     *
     * @param  integer $permissions The permissions octal.
     * @return boolean
     */
    public static function createDir($path, $permissions = 0777)
    {
        if (!is_dir($path)) {
            return mkdir($path, $permissions, true);
        }

        return true;
    }

    /**
     * List files in a directory.
     */
    public static function listFiles($dir, $pattern = '*', $order = 'asc')
    {
        $files = array();

        $globPath = rtrim($dir, '/') . '/' . $pattern;
        $fileGlob = glob($globPath);

        foreach ($fileGlob as $path) {
            if (is_file($path)) {
                $files[] = $path;
            }
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
    public static function deleteDir($dir)
    {
        $files = self::listFiles($dir);

        // Delete all
        foreach ($files as $file) {
            unlink($file);
        }

        return rmdir($dir);
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
