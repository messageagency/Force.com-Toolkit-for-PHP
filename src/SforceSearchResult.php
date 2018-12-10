<?php

namespace DeveloperForce\PhpToolkit;

class SforceSearchResult
{
  public $searchRecords;

  public function __construct($response)
  {

    if($response instanceof SforceSearchResult) {
      $this->searchRecords = $response->searchRecords;
    } else {
      $this->searchRecords = array();
      if (isset($response->searchRecords)) {
        if (is_array($response->searchRecords)) {
          foreach ($response->searchRecords as $record) {
            $sobject = new SObject($record->record);
            array_push($this->searchRecords, $sobject);
          };
        } else {
          $sobject = new SObject($response->searchRecords->record);
          array_push($this->records, $sobject);
        }
      }
    }
  }
}
