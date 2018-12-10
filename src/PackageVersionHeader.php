<?php

namespace DeveloperForce\PhpToolkit;

class PackageVersionHeader
{
    /**
     * @var array $packageVersions
     */
    public $packageVersions;

    /**
     * Class constructor.
     *
     * @param  array $packageVersions
     * @return void
     */
    public function __construct($packageVersions)
    {
        $this->packageVersions = $packageVersions;
    }
}

