<?php

namespace DeveloperForce\PhpToolkit;

class UserTerritoryDeleteHeader
{
  public $transferToUserId;

  public function __construct($transferToUserId)
  {
    $this->transferToUserId = $transferToUserId;
  }
}