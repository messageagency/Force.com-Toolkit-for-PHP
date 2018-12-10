<?php

namespace DeveloperForce\PhpToolkit\HeaderOptions;

class UserTerritoryDeleteHeader
{
    public $transferToUserId;

    public function __construct($transferToUserId)
    {
        $this->transferToUserId = $transferToUserId;
    }
}
