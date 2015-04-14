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

define('TRIDENT_ERROR_XML_LIB_NO_ARRAY', 201);

class Xml_Library extends Trident_Abstract_Library
{

    /**
     * Write xml string.
     *
     * @param string $objects_name Name of the objects.
     * @param string $object_name Name of individual object.
     * @param array $data Data 2D array.
     *
     * @return string|bool XML formed string on success, false boolean on failure.
     */
    public function write_xml_to_string($objects_name, $object_name, $data = [])
    {
        if (!is_array($data))
        {
            error_log("XML Library data must be an array");
            return false;
        }
        $rows = ['<?xml version="1.0" encoding="UTF-8"?>', "<$objects_name>"];
        foreach ($data as $row)
        {
            $object = ["\t<$object_name>"];
            if (!is_array($row))
            {
                error_log("XML Library data must be an array");
                return false;
            }
            foreach ($row as $key => $value)
            {
                $object[] = "\t\t<$key>$value</$key>";
            }
            $object[] = "\t</$object_name>";
            $rows[] = implode(PHP_EOL, $object);
        }
        $rows[] = "</$objects_name>";
        $data = implode(PHP_EOL, $rows);
        return $data;
    }

    /**
     * Write xml file.
     *
     * @param string $file File path.
     * @param string $objects_name Name of the objects.
     * @param string $object_name Name of individual object.
     * @param array $data Data 2D array.
     *
     * @return bool True on successful write, false otherwise.
     */
    public function write_xml_to_file($file, $objects_name, $object_name, $data = [])
    {
        $data = $this->write_xml_to_string($objects_name, $object_name, $data);
        if ($data !== false)
        {
            return $this->io->write_file($file, $data);
        }
        return false;
    }

} 