<?php

namespace talbertsutah\UUtahSemester\Exceptions;

use Exception;

/**
 * Exception thrown when invalid input is provided to the Semester constructor
 */
class SemesterInvalidConstructorInput extends Exception
{
    /**
     * Create a new SemesterInvalidConstructorInput exception
     *
     * @param mixed $value The invalid value provided
     * @param string $message Optional custom message
     * @param int $code Exception code
     */
    public function __construct($value, string $message = "", int $code = 0)
    {
        if (empty($message)) {
            $type = gettype($value);
            $message = "Invalid input for Semester constructor. Expected int or string, got {$type}";
            if (is_string($value)) {
                $message .= ": '{$value}'";
            }
        }

        parent::__construct($message, $code);
    }

    /**
     * Get the error message (for backwards compatibility)
     *
     * @return string
     */
    public function errorMessage(): string
    {
        return $this->getMessage();
    }
}
