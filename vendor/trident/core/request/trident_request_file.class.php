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
 * Class Trident_Request_File.
 * Uploaded file wrapper class for easier handling of uploaded files.
 */
class Trident_Request_File
{

    /**
     * Temporary name of the file.
     *
     * @var string
     */
    public $temporary_name;

    /**
     * Name of the file.
     *
     * @var string
     */
    public $name;

    /**
     * Size of the file in bytes.
     *
     * @var int
     */
    public $size;

    /**
     * File upload error code.
     *
     * @var int
     */
    public $error;

    /**
     * File mime type.
     *
     * @var string
     */
    public $mime;

    /**
     * Read mime type from the file instead of using the uploaded mime type that can be manipulated.
     *
     * @param int    $error          Error code.
     * @param string $name           File name.
     * @param string $temporary_name Temporary file name.
     * @param int    $size           File size.
     *
     * @throws Trident_Exception
     */
    function __construct($error, $name, $temporary_name, $size)
    {
        if ($error === UPLOAD_ERR_OK && !is_readable($temporary_name))
        {
            error_log("Trident framework: Can't create file object because temporary file $temporary_name doesn't exists or is not readable");
            http_response(500);
        }
        $this->error = $error;
        $this->name = $name;
        $this->temporary_name = $temporary_name;
        $this->size = $size;
        if ($error === UPLOAD_ERR_OK)
        {
            $file_info = finfo_open(FILEINFO_MIME_TYPE);
            $this->mime = finfo_file($file_info, $this->temporary_name);
        }
    }

    /**
     * Saves the uploaded temporary file to a file.
     *
     * @param string $file File path.
     *
     * @return bool True on success, false otherwise.
     * @throws Trident_Exception
     */
    public function save($file)
    {
        if ($this->error !== UPLOAD_ERR_OK)
        {
            $error = $this->error;
            $name = $this->name;
            error_log("Trident framework: Can't save request file $name to $file. Upload error: $error");
            http_response(500);
        }
        return move_uploaded_file($this->temporary_name, $file);
    }

    /**
     * Validate that the uploaded file is an image.
     *
     * @return bool True if the file is an image, false otherwise.
     */
    public function is_image()
    {
        return getimagesize($this->temporary_name) ? true : false;
    }

    /**
     * Deletes the temporary file.
     */
    public function delete()
    {
        if (file_exists($this->temporary_name))
        {
            unlink($this->temporary_name);
        }
    }

    /**
     * Get a pointer to the file for BLOB writing.
     *
     * @param string $mode Opening mode.
     *
     * @return null|resource Pointer to the file on success, null otherwise.
     */
    public function get_file_pointer($mode = 'rb')
    {
        if (is_readable($this->temporary_name))
        {
            return fopen($this->temporary_name, $mode);
        }
        return null;
    }
}