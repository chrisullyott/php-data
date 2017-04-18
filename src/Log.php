<?php

/**
 * Logs data in JSON.
 *
 * @author Chris Ullyott <chris@monkdevelopment.com>
 */
class Log
{
    private $file;

    public function __construct($file)
    {
        $this->file = $file;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function get($key)
    {
        $array = $this->getAll();

        return isset($array[$key]) ? $array[$key] : null;
    }

    public function getAll()
    {
        $json = self::read($this->getFile());
        $array = json_decode($json, true);

        return is_array($array) ? $array : array();
    }

    public function getCount()
    {
        return count($this->getAll());
    }

    public function set($key, $value)
    {
        $array = $this->getAll();

        $array[$key] = $value;
        $json = json_encode($array);

        return self::write($this->getFile(), $json);
    }

    private static function read($path)
    {
        if (is_readable($path)) {
            return file_get_contents($path);
        }

        return null;
    }

    private static function write($path, $contents, $mode = 'w')
    {
        $handle = fopen($path, $mode);

        if (flock($handle, LOCK_EX)) {
            fwrite($handle, $contents);
            fflush($handle);
            flock($handle, LOCK_UN);
        }

        return fclose($handle);
    }

}
