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
        return $this->io->write_file($file, $this->write_csv_to_string($data));
    }
} 