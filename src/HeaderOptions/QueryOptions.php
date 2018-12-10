<?php

namespace DeveloperForce\PhpToolkit\HeaderOptions;

/**
 * To be used with Retrieve, Query, and QueryMore operations.
 *
 * @package SalesforceSoapClient
 */
class QueryOptions
{
    /**
     *
     *
     * @var int
     */
    public $batchSize;

    /**
     * @param int $limit Batch size
     */
    public function __construct($limit = 500)
    {
        $this->validateLimit($limit);
        $this->batchSize = $limit;
    }

    /**
     * @param int $limit
     *
     * @throws Exception
     */
    private function validateLimit($limit)
    {
        if ($limit < 200) {
            throw new \Exception('The batch size limit can\'t be lower than 200');
        }
        if ($limit > 2000) {
            throw new \Exception('The batch size limit can\'t be higher than 2000');
        }
    }
}
