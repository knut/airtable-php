<?php
namespace Airtable\Exception;

/**
 * The base exception for Airtable API errors
 *
 * @package Airtable\Exception
 */
class ApiErrorException extends \Exception {

    /**
     * Creates a instance of the \Airtable\Exception
     *
     * @param string $message The error message
     * @param int|null $code The error code
     * @param \Exception|null $previous The original exception
     */
    public function __construct($message, $code = null, \Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }

}
