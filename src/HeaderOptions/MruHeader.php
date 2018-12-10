<?php

namespace DeveloperForce\PhpToolkit\HeaderOptions;

/**
 * To be used with Create and Update operations.
 *
 * @package SalesforceSoapClient
 */
class MruHeader
{
    // boolean that Indicates whether to update the list of most recently used items (True) or not (False).
    public $updateMruFlag;

    public function __construct($bool)
    {
        $this->updateMruFlag = $bool;
    }
}
