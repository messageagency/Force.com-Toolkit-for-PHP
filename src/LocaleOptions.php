<?php

namespace DeveloperForce\PhpToolkit;

class LocaleOptions
{
  public $language;

  /**
   * Class constructor.
   *
   * @param  string $language
   * @return void
   */
  public function __construct($language)
  {
    $this->language = $language;
  }
}
