<?php

/**
 * Class Trident_Exception
 *
 * Simple extension of the Exception class, adding a constant prefix to the exception message
 */
class Trident_Exception extends Exception
{

    /**
     * Constructor
     *
     * Appends the prefix "Trident Framework: " to the exception message.
     *
     * @param string    $message exception message
     * @param int       $code exception code
     * @param Exception $previous previous exception
     */
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        $message = "Trident Framework: " . $message;
        parent::__construct($message, $code, $previous);
    }
}