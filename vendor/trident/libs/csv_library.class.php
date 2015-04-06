<?php

define('TRIDENT_ERROR_CSV_LIB_NO_ARRAY', 101);

/**
 * Class Csv_Library
 *
 * Simple csv writer.
 */
class Csv_Library extends Trident_Abstract_Library
{

    /**
     * Write csv string
     *
     * @param array $data
     *
     * @return string
     * @throws Trident_Exception
     */
    public function write_csv_to_string($data = [])
    {
        if (!is_array($data))
        {
            throw new Trident_Exception("CSV Library data must be an array", TRIDENT_ERROR_CSV_LIB_NO_ARRAY);
        }
        $rows = [];
        foreach ($data as $row)
        {
            if (is_array($row))
            {
                $row = implode('", "', $row);
            }
            $rows[] = "\"$row\"";
        }
        $data = implode(PHP_EOL, $rows);
        return $data;
    }

    /**
     * Write csv file
     *
     * @param string $file file path
     * @param array $data
     *
     * @return bool
     * @throws Trident_Exception
     */
    public function write_csv_to_file($file, $data = [])
    {
        return $this->_io->write_file($file, $this->write_csv_to_string($data));
    }
} 