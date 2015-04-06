<?php
/**
 * Trident Framework - PHP MVC Framework
 *
 * The MIT License (MIT)
 * Copyright (c) 2015 Ron Dadon
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * Class Trident_IO
 *
 * File IO handling
 */
class Trident_IO
{

    /**
     * Empty file
     *
     * @param string $file file path
     *
     * @return bool
     */
    public function truncate_file($file)
    {
        return $this->write_file($file, '');
    }

    /**
     * Read file content
     *
     * @param string $file file path
     * @param bool $lock exclusively lock the file
     *
     * @return bool|string
     */
    public function read_file($file, $lock = false)
    {
        if (file_exists($file) && !is_readable($file))
        {
            return false;
        }
        return file_get_contents($file, $lock ? LOCK_EX : null);
    }

    /**
     * Write data to a file
     *
     * @param string $file file path
     * @param string $data file data
     *
     * @return bool
     */
    public function write_file($file, $data)
    {
        if (file_exists($file) && !is_writable($file))
        {
            return false;
        }
        return file_put_contents($file, $data, LOCK_EX) !== false;
    }

    /**
     * Copy file
     *
     * @param string $from from path
     * @param string $to to path
     *
     * @return bool
     */
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

    /**
     * Move file (copy and delete the source)
     *
     * @param string $from from path
     * @param string $to to path
     *
     * @return bool
     */
    public function move_file($from, $to)
    {
        return $this->copy_file($from, $to) && $this->delete_file($from);
    }

    /**
     * Delete a file
     *
     * @param string $file file path
     *
     * @return bool
     */
    public function delete_file($file)
    {
        if (!is_writable($file))
        {
            return false;
        }
        return unlink($file);
    }

    /**
     * Create directory
     *
     * @param string $dir directory path
     * @param int  $mode directory mode
     * @param bool $recursive use recursive creation
     *
     * @return bool
     */
    public function create_directory($dir, $mode = 0777, $recursive = false)
    {
        return mkdir($dir, $mode, $recursive);
    }

    /**
     * Delete a directory (truncate the directory and delete it)
     *
     * @param string $dir directory path
     *
     * @return bool
     */
    public function delete_directory($dir)
    {
        $this->truncate_directory($dir);
        return rmdir($dir);
    }

    /**
     * Delete directory content
     *
     * @param string $dir directory path
     */
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

    /**
     * Get file size
     *
     * @param string $file file path
     * @param int $unit size unit
     *
     * @return bool|int
     */
    public function get_file_size($file, $unit = TRIDENT_IO_BYTE)
    {
        if (!is_readable($file))
        {
            return false;
        }
        return filesize($file) / $unit;
    }

    /**
     * Get file mime type
     *
     * @param string $file file path
     *
     * @return string|bool
     */
    public function get_mime_type($file)
    {
        if (!is_readable($file))
        {
            return false;
        }
        $file_info = finfo_open(FILEINFO_MIME_TYPE);
        $mime =  finfo_file($file_info, $file);
        return $mime;
    }
}