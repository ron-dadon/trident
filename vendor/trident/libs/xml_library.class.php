<?php

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
        return $this->_io->write_file($file, $this->write_xml_to_string($objects_name, $object_name, $data));
    }

} 