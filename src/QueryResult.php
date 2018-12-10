<?php

namespace DeveloperForce\PhpToolkit;

class QueryResult implements \Iterator
{
    public $queryLocator;
    public $done;
    public $records;
    public $size;

    public $pointer;

    /**
     * @var \DeveloperForce\PhpToolkit\SforceBaseClient
     */
    private $sf;

    public function __construct($response)
    {
        $this->queryLocator = $response->queryLocator;
        $this->done = $response->done;
        $this->size = $response->size;

        $this->pointer = 0;
        $this->sf = false;

        if($response instanceof QueryResult) {
            $this->records = $response->records;
        }
        else {
            $this->records = array();
            if (isset($response->records)) {
                if (is_array($response->records)) {
                    foreach ($response->records as $record) {
                        array_push($this->records, $record);
                    };
                }
                else {
                    $this->records = [];
                }
            }
        }
    }

    public function setSf(SforceBaseClient $sf)
    {
        $this->sf = $sf;
    } // Dependency Injection

    // Basic Iterator implementation functions
    public function rewind()
    {
        $this->pointer = 0;
    }
    public function next()
    {
        ++$this->pointer;
    }
    public function key()
    {
        return $this->pointer;
    }
    public function current()
    {
        return new SObject($this->records[$this->pointer]);
    }

    public function valid()
    {
        while ($this->pointer >= count($this->records)) {
            // Pointer is larger than (current) result set; see if we can fetch more
            if ($this->done === false) {
                if ($this->sf === false) { throw new \Exception("Dependency not met!");
                }
                $response = $this->sf->queryMore($this->queryLocator);
                $this->records = array_merge($this->records, $response->records); // Append more results
                $this->done = $response->done;
                $this->queryLocator = $response->queryLocator;
            } else {
                return false; // No more records to fetch
            }
        }
        if (isset($this->records[$this->pointer])) { return true;
        }

        throw new \Exception("QueryResult has gaps in the record data?");
    }
}
