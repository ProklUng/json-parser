<?php

namespace Cerbero\JsonParser\Exceptions;

use Exception;

/**
 * The exception thrown when the JSON syntax is not valid.
 *
 */
final class SyntaxException extends Exception implements JsonParserException
{
    /**
     * The error position.
     *
     * @var int|null
     */
    public $position = null;

    /**
     * @var string
     */
    public $value;

    /**
     * Instantiate the class
     *
     * @param string $value
     * @param bool   $overwriteMessage
     */
    public function __construct(string $value, bool $overwriteMessage = false)
    {
        $this->value = $value;
        $message = "Syntax error: unexpected '$value'";
        if ($overwriteMessage) {
            $message = $this->value;
        }

        parent::__construct($message);
    }

    /**
     * Set the error position
     *
     * @param int $position
     * @return self
     */
    public function setPosition(int $position): self
    {
        $this->position = $position;
        $this->message .= " at position {$position}";

        return $this;
    }
}
