<?php

/**
 * Class Trident_Request_File
 *
 * Uploaded file wrapper class for easier handling of uploaded files.
 */
class Trident_Request_File
{

    public $temporary_name;
    public $name;
    public $size;
    public $error;
    public $mime;

    /**
     * Constructor
     *
     * Read mime type from the file instead of using the uploaded mime type that can be manipulated.
     *
     * @param int    $error          error code
     * @param string $name           file name
     * @param string $temporary_name temporary file name
     * @param int    $size           file size
     *
     * @throws Trident_Exception
     */
    function __construct($error, $name, $temporary_name, $size)
    {
        if ($error === UPLOAD_ERR_OK && !is_readable($temporary_name))
        {
            throw new Trident_Exception("Can't create file object because temporary file $temporary_name doesn't exists or is not readable");
        }
        $this->error = $error;
        $this->name = $name;
        $this->temporary_name = $temporary_name;
        $this->size = $size;
        if ($error === UPLOAD_ERR_OK)
        {
            $file_info = finfo_open(FILEINFO_MIME_TYPE);
            $this->mime =  finfo_file($file_info, $this->temporary_name);
        }
    }

    /**
     * Saves the uploaded temporary file to a file
     *
     * @param string $file file path
     *
     * @return bool
     * @throws Trident_Exception
     */
    public function save($file)
    {
        if ($this->error !== UPLOAD_ERR_OK)
        {
            $error = $this->error;
            $name = $this->name;
            throw new Trident_Exception("Can't save request file $name to $file. Upload error: $error");
        }
        return move_uploaded_file($this->temporary_name, $file);
    }

    /**
     * Validate that the uploaded file is an image
     *
     * @return bool
     */
    public function is_image()
    {
        return getimagesize($this->temporary_name) ? true : false;
    }

    /**
     * Deletes the temporary file
     */
    public function delete()
    {
        if (file_exists($this->temporary_name))
        {
            unlink($this->temporary_name);
        }
    }

    /**
     * Get a pointer to the file for BLOB writing
     *
     * @param string $mode opening mode
     *
     * @return null|resource
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