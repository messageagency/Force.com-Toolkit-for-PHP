<?php

namespace DeveloperForce\PhpToolkit;

/**
 * To be used with Create and Update operations.
 * Only one attribute can be set at a time.
 *
 * @package SalesforceSoapClient
 */
class AssignmentRuleHeader
{
    // int
    public $assignmentRuleId;
    // boolean
    public $useDefaultRuleFlag;

    /**
     * Constructor.  Only one param can be set.
     *
     * @param int     $id   AssignmentRuleId
     * @param boolean $flag UseDefaultRule flag
     */
    public function __construct($id = null, $flag = null)
    {
        if ($id != null) {
            $this->assignmentRuleId = $id;
        }
        if ($flag != null) {
            $this->useDefaultRuleFlag = $flag;
        }
    }
}
