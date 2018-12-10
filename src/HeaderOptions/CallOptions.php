<?php

namespace DeveloperForce\PhpToolkit\HeaderOptions;

class CallOptions
{
    public $client;
    public $defaultNamespace;

    public function __construct($client, $defaultNamespace = null)
    {
        $this->client = $client;
        $this->defaultNamespace = $defaultNamespace;
    }
}
