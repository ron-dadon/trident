<?php

class Trident_IO
{

    public function truncate_file($file)
    {
        return $this->write_file($file, '');
    }

    public function read_file($file, $lock = false)
    {
        if (file_exists($file) && !is_readable($file))
        {
            return false;
        }
        return file_get_contents($file, $lock ? LOCK_EX : null);
    }

    public function write_file($file, $data)
    {
        if (file_exists($file) && !is_writable($file))
        {
            return false;
        }
        return file_put_contents($file, $data, LOCK_EX) !== false;
    }

    public function copy_file($from, $to)
    {
        if (!is_readable($from))
        {
            return false;
        }
        if (file_exists($to) && !is_writable($to))
        {
            return false;
        }
        return copy($from, $to);
    }

    public function move_file($from, $to)
    {
        return $this->copy_file($from, $to) && $this->delete_file($from);
    }

    public function delete_file($file)
    {
        if (!is_writable($file))
        {
            return false;
        }
        return unlink($file);
    }

    public function create_directory($dir, $mode = 0777, $recursive = false)
    {
        return mkdir($dir, $mode, $recursive);
    }

    public function delete_directory($dir)
    {
        $this->truncate_directory($dir);
        return rmdir($dir);
    }

    public function truncate_directory($dir)
    {
        $paths = array_diff(scandir($dir), ['.', '..']);
        $dir .= DS;
        foreach ($paths as $path)
        {
            $path = $dir . $path;
            (is_dir($path)) ? $this->delete_directory($path) : $this->delete_file($path);
        }
    }
}