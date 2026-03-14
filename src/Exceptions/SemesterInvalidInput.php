<?php

namespace talbertsutah\UUtahSemester\Exceptions;

use Exception;

/**
 * Exception thrown when an invalid semester code or string is provided
 */
class SemesterInvalidInput extends Exception
{
    /**
     * Create a new SemesterInvalidInput exception
     *
     * @param mixed $value The invalid value
     * @param string $type Either 'code' or 'string' to indicate the type of value
     * @param int $code Exception code
     */
    public function __construct($value, string $type = "semester", int $code = 0)
    {
        $message = "Invalid {$type} input";
        
        if (is_int($value)) {
            $message .= ": {$value} is not a valid semester code";
        } elseif (is_string($value)) {
            $message .= ": '{$value}' is not a valid semester string";
        } else {
            $message .= ": " . gettype($value);
        }

        parent::__construct($message, $code);
    }
}
