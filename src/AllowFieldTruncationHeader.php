<?php

namespace DeveloperForce\PhpToolkit;

class AllowFieldTruncationHeader
{
    public $allowFieldTruncation;

    public function __construct($allowFieldTruncation)
    {
        $this->allowFieldTruncation = $allowFieldTruncation;
    }
}
