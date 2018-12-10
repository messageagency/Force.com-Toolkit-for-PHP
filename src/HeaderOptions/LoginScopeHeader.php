<?php

namespace DeveloperForce\PhpToolkit\HeaderOptions;

/**
 * To be used with the Login operation.
 *
 * @package SalesforceSoapClient
 */
class LoginScopeHeader
{
    // boolean that Indicates whether to update the list of most recently used items (True) or not (False).
    public $organizationId;
    public $portalId;

    public function __construct($orgId = null, $portalId = null)
    {
        $this->organizationId = $orgId;
        $this->portalId = $portalId;
    }
}
