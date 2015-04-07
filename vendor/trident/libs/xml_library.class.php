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
     * Write xml string
     *
     * @param string $objects_name
     * @param string $object_name
     * @param array $data
     *
     * @return array|string
     * @throws Trident_Exception
     */
    public function write_xml_to_string($objects_name, $object_name, $data = [])
    {
        if (!is_array($data))
        {
            throw new Trident_Exception("XML Library data must be an array", TRIDENT_ERROR_XML_LIB_NO_ARRAY);
        }
        $rows = ['<?xml version="1.0" encoding="UTF-8"?>', "<$objects_name>"];
        foreach ($data as $row)
        {
            $object = ["\t<$object_name>"];
            if (!is_array($row))
            {
                throw new Trident_Exception("XML Library data must be an array", TRIDENT_ERROR_XML_LIB_NO_ARRAY);
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
     * Write xml file
     *
     * @param string $file file path
     * @param string $objects_name
     * @param string $object_name
     * @param array $data
     *
     * @return bool
     * @throws Trident_Exception
     */
    public function write_xml_to_file($file, $objects_name, $object_name, $data = [])
    {
        return $this->io->write_file($file, $this->write_xml_to_string($objects_name, $object_name, $data));
    }

} 