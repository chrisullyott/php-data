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

    private static function createDir($path, $permissions = 0777)
    {
        if (!is_dir($path)) {
            return mkdir($path, $permissions, true);
        }

        return true;
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

        $this->save($array);

        return $this;
    }

    public function merge(array $array)
    {
       $array = array_merge($this->getAll(), $array);

       $this->save($array);

       return $this;
    }

    public function delete($key)
    {
        $array = $this->getAll();

        if (isset($array[$key])) {
            unset($array[$key]);
        }

        $this->save($array);

        return $this;
    }

    public function deleteValue($value)
    {
        $array = $this->getAll();

        $key = array_search($value, $array);

        if (isset($array[$key])) {
            unset($array[$key]);
        }

        $this->save($array);

        return $this;
    }

    private function save(array $array)
    {
        if (count($array) > 0) {
            self::createDir(dirname($this->getFile()));
            return self::write($this->getFile(), json_encode($array));
        }

        return false;
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

    public function unlink()
    {
        if (file_exists($this->getFile())) {
            return unlink($this->getFile());
        }

        return file_exists($this->getFile());
    }

}
