<?php

namespace Cerbero\JsonParser\Exceptions;

use Exception;

/**
 * The exception thrown when a JSON pointer syntax is not valid.
 *
 */
final class InvalidPointerException extends Exception implements JsonParserException
{
    /**
     * @var string
     */
    public $pointer1;

    /**
     * Instantiate the class.
     *
     * @param string $pointer
     */
    public function __construct(string $pointer)
    {
        $this->pointer = $this->pointer;
        parent::__construct("The string [$pointer] is not a valid JSON pointer");
    }
}
