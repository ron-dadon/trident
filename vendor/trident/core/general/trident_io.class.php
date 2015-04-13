<?php
/**
 * Trident Framework - PHP MVC Framework
 * The MIT License (MIT)
 * Copyright (c) 2015 Ron Dadon
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * Class Trident_IO.
 * File IO functions.
 */
class Trident_IO
{

    /**
     * Empty file.
     *
     * @param string $file File path.
     *
     * @return bool True on success, false otherwise.
     */
    public function truncate_file($file)
    {
        return $this->write_file($file, '');
    }

    /**
     * Read file content.
     *
     * @param string $file File path.
     * @param bool   $lock Exclusively lock the file.
     *
     * @return bool|string The content of the file on success, false boolean value on failure.
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
     * Write data to a file.
     *
     * @param string $file File path.
     * @param string $data File data.
     *
     * @return bool True on success, false otherwise.
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
     * Copy file.
     *
     * @param string $source      From path.
     * @param string $destination To path.
     *
     * @return bool True on success, false otherwise.
     */
    public function copy_file($source, $destination)
    {
        if (!is_readable($source))
        {
            return false;
        }
        if (file_exists($destination) && !is_writable($destination))
        {
            return false;
        }
        return copy($source, $destination);
    }

    /**
     * Move file (copy and delete the source).
     *
     * @param string $source      From path.
     * @param string $destination To path.
     *
     * @return bool True on success, false otherwise.
     */
    public function move_file($source, $destination)
    {
        return $this->copy_file($source, $destination) && $this->delete_file($source);
    }

    /**
     * Delete a file.
     *
     * @param string $file File path.
     *
     * @return bool True on success, false otherwise.
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
     * Create a directory.
     *
     * @param string $dir       Directory path.
     * @param int    $mode      Directory mode.
     * @param bool   $recursive Use recursive creation.
     *
     * @return bool True on success, false otherwise.
     */
    public function create_directory($dir, $mode = 0777, $recursive = false)
    {
        return mkdir($dir, $mode, $recursive);
    }

    /**
     * Delete a directory (truncate the directory and delete it).
     *
     * @param string $dir Directory path.
     *
     * @return bool True on success, false otherwise.
     */
    public function delete_directory($dir)
    {
        $this->truncate_directory($dir);
        return rmdir($dir);
    }

    /**
     * Delete directory content.
     *
     * @param string $dir Directory path.
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
     * Get file size.
     *
     * @param string $file File path.
     * @param int    $unit Size unit.
     *
     * @return bool|int File size on success, false boolean on failure.
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
     * Get file mime type.
     *
     * @param string $file File path.
     *
     * @return string|bool File's mime type on success, false boolean on failure.
     */
    public function get_mime_type($file)
    {
        if (!is_readable($file))
        {
            return false;
        }
        $file_info = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($file_info, $file);
        return $mime;
    }
}