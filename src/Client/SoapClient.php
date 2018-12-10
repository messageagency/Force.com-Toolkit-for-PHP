<?php

namespace DeveloperForce\PhpToolkit\Client;

use SoapClient as PhpSoapClient;
use DOMDocument;

/**
 * SforceSoapClient class.
 *
 * @package SalesforceSoapClient
 *
 * When parsing partner WSDL, when PHP SOAP sees NewValue and OldValue, since
 * the element has a xsi:type attribute with value 'string', it drops the
 * string content into the parsed output and loses the tag name. Removing the
 * xsi:type forces PHP SOAP to just leave the tags intact
 */
class SoapClient extends PhpSoapClient
{
    function __doRequest($request, $location, $action, $version, $one_way = 0)
    {
        $response = parent::__doRequest($request, $location, $action, $version, $one_way);

        // Quick check to only parse the XML here if we think we need to
        if (strpos($response, '<sf:OldValue') === false && strpos($response, '<sf:NewValue') === false) {
            return $response;
        }

        $dom = new DOMDocument();
        $dom->loadXML($response);

        $nodeList = $dom->getElementsByTagName('NewValue');
        foreach ($nodeList as $node) {
            $node->removeAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'type');
        }
        $nodeList = $dom->getElementsByTagName('OldValue');
        foreach ($nodeList as $node) {
            $node->removeAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'type');
        }

        return $dom->saveXML();
    }
}
