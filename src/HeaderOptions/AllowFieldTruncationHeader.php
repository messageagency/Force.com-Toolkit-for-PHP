<?php

namespace DeveloperForce\PhpToolkit\HeaderOptions;

class AllowFieldTruncationHeader
{
    public $allowFieldTruncation;

    public function __construct($allowFieldTruncation)
    {
        $this->allowFieldTruncation = $allowFieldTruncation;
    }
}
