<?php

namespace DeveloperForce\PhpToolkit;

use stdClass;
use Exception;

class SObject
{
    public $type;
    public $fields;
    //    public $sobject;

    public function __construct($response = null)
    {
        if (!isset($response) && !$response) {
            return;
        }

        foreach ($response as $responseKey => $responseValue) {
            if (in_array(strval($responseKey), array('Id', 'type', 'any'))) {
                continue;
            }
            $this->$responseKey = $responseValue;
        }

        if (isset($response->Id)) {
            $this->Id = is_array($response->Id) ? $response->Id[0] : $response->Id;
        }

        if (isset($response->type)) {
            $this->type = $response->type;
        }

        if (isset($response->any)) {
            try {
                //$this->fields = $this->convertFields($response->any);
                // If ANY is an object, instantiate another SObject
                if ($response->any instanceof stdClass) {
                    if ($this->isSObject($response->any)) {
                        $anArray = array();
                        $sobject = new SObject($response->any);
                        $anArray[] = $sobject;
                        $this->sobjects = $anArray;
                    } else {
                        // this is for parent to child relationships
                        $this->queryResult = new QueryResult($response->any);
                    }
                } else {
                    // If ANY is an array
                    if (is_array($response->any)) {
                        // Loop through each and perform some action.
                        $anArray = array();

                        // Modify the foreach to have $key=>$value
                        // Added on 28th April 2008
                        foreach ($response->any as $key => $item) {
                            if ($item instanceof stdClass) {
                                if ($this->isSObject($item)) {
                                    $sobject = new SObject($item);
                                    // make an associative array instead of a numeric one
                                    $anArray[$key] = $sobject;
                                } else {
                                          // this is for parent to child relationships
                                          //$this->queryResult = new QueryResult($item);
                                    if (!isset($this->queryResult)) {
                                        $this->queryResult = array();
                                    }
                                    array_push($this->queryResult, new QueryResult($item));
                                }
                            } else {
                                //$this->fields = $this->convertFields($item);

                                if (strpos($item, 'sf:') === false) {
                                    $currentXmlValue = sprintf('<sf:%s>%s</sf:%s>', $key, $item, $key);
                                } else {
                                    $currentXmlValue = $item;
                                }

                                if (!isset($fieldsToConvert)) {
                                    $fieldsToConvert = $currentXmlValue;
                                } else {
                                    $fieldsToConvert .= $currentXmlValue;
                                }
                            }
                        }

                        if (isset($fieldsToConvert)) {
                            // If this line is commented, then the fields becomes a stdclass object and does not have the name variable
                            // In this case the foreach loop on line 252 runs successfuly
                            $this->fields = $this->convertFields($fieldsToConvert);
                        }

                        if (sizeof($anArray) > 0) {
                            // To add more variables to the the top level sobject
                            foreach ($anArray as $key => $children_sobject) {
                                $this->fields->$key = $children_sobject;
                            }
                            //array_push($this->fields, $anArray);
                            // Uncommented on 28th April since all the sobjects have now been moved to the fields
                            //$this->sobjects = $anArray;
                        }

                        /*
                        $this->fields = $this->convertFields($response->any[0]);
                        if (isset($response->any[1]->records)) {
                        $anArray = array();
                        if ($response->any[1]->size == 1) {
                        $records = array (
                        $response->any[1]->records
                        );
                        } else {
                        $records = $response->any[1]->records;
                        }
                        foreach ($records as $record) {
                        $sobject = new SObject($record);
                        array_push($anArray, $sobject);
                        }
                        $this->sobjects = $anArray;
                        } else {
                        $anArray = array();
                        $sobject = new SObject($response->any[1]);
                        array_push($anArray, $sobject);
                        $this->sobjects = $anArray;
                        }
                        */
                    } else {
                        $this->fields = $this->convertFields($response->any);
                    }
                }
            } catch (Exception $e) {
                var_dump('exception: ', $e);
            }
        }
    }

    function __get($name)
    {
        return (isset($this->fields->$name))? $this->fields->$name : false;
    }
    function __isset($name)
    {
        return isset($this->fields->$name);
    }

    /**
     * Parse the "any" string from an sObject.  First strip out the sf: and then
     * enclose string with <Object></Object>.  Load the string using
     * simplexml_load_string and return an array that can be traversed.
     */
    function convertFields($any)
    {
        $str = preg_replace('{sf:}', '', $any);

        $array = $this->xml2array('<Object xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">'.$str.'</Object>', 2);

        $xml = new stdClass();
        if (!count($array['Object'])) {
            return $xml;
        }

        foreach ($array['Object'] as $k => $v) {
            $xml->$k = $v;
        }

        //$new_string = '<Object xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">'.$new_string.'</Object>';
        //$new_string = $new_string;
        //$xml = simplexml_load_string($new_string);
        return $xml;
    }

    /**
     *
     * @param  string $contents
     * @return array
     */
    function xml2array($contents, $get_attributes = 1)
    {
        if (!$contents) {
            return array();
        }

        if (!function_exists('xml_parser_create')) {
            //print "'xml_parser_create()' function not found!";
            return array('not found');
        }
        //Get the XML parser of PHP - PHP must have this module for the parser to work
        $parser = xml_parser_create();
        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
        xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
        xml_parse_into_struct($parser, $contents, $xml_values);
        xml_parser_free($parser);

        if (!$xml_values) {
            return;//Hmm...
        }

        //Initializations
        $xml_array = array();
        $parents = array();
        $opened_tags = array();
        $arr = array();

        $current = &$xml_array;

        //Go through the tags.
        foreach ($xml_values as $data) {
            unset($attributes, $value);//Remove existing values, or there will be trouble

            //This command will extract these variables into the foreach scope
            // tag(string), type(string), level(int), attributes(array).
            extract($data);//We could use the array by itself, but this cooler.

            $result = '';
            if ($get_attributes) {
                switch ($get_attributes) {
                case 1:
                    $result = array();
                    if (isset($value)) {
                        $result['value'] = $value;
                    }

                    //Set the attributes too.
                    if (isset($attributes)) {
                        foreach ($attributes as $attr => $val) {
                            if ($get_attributes == 1) {
                                $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
                            }
                            /**
                             * :TODO: should we change the key name to '_attr'? Someone may use the tagname 'attr'. Same goes for 'value' too
                             */
                        }
                    }
                    break;

                case 2:
                    $result = array();
                    if (isset($value)) {
                        $result = $value;
                    }

                    //Check for nil and ignore other attributes.
                    if (isset($attributes) && isset($attributes['xsi:nil']) && !strcasecmp($attributes['xsi:nil'], 'true')) {
                        $result = null;
                    }
                    break;
                }
            } elseif (isset($value)) {
                $result = $value;
            }

            //See tag status and do the needed.
            if ($type == "open") {//The starting of the tag '<tag>'
                $parent[$level-1] = &$current;

                if (!is_array($current) or (!in_array($tag, array_keys($current)))) { //Insert New tag
                    $current[$tag] = $result;
                    $current = &$current[$tag];
                } else { //There was another element with the same tag name
                    if (isset($current[$tag][0])) {
                        array_push($current[$tag], $result);
                    } else {
                        $current[$tag] = array($current[$tag],$result);
                    }
                    $last = count($current[$tag]) - 1;
                    $current = &$current[$tag][$last];
                }
            } elseif ($type == "complete") { //Tags that ends in 1 line '<tag />'
                //See if the key is already taken.
                if (!isset($current[$tag])) { //New Key
                    $current[$tag] = $result;
                } else { //If taken, put all things inside a list(array)
                    if ((is_array($current[$tag]) and $get_attributes == 0)//If it is already an array...
                        or (isset($current[$tag][0]) and is_array($current[$tag][0]) and ($get_attributes == 1 || $get_attributes == 2))
                    ) {
                        array_push($current[$tag], $result); // ...push the new element into that array.
                    } else { //If it is not an array...
                        $current[$tag] = array($current[$tag],$result); //...Make it an array using using the existing value and the new value
                    }
                }
            } elseif ($type == 'close') { //End of tag '</tag>'
                $current = &$parent[$level-1];
            }
        }

        return($xml_array);
    }

    /*
    * If the stdClass has a done, we know it is a QueryResult
    */
    function isQueryResult($param)
    {
        return isset($param->done);
    }

    /*
    * If the stdClass has a type, we know it is an SObject
    */
    function isSObject($param)
    {
        return isset($param->type);
    }
}
