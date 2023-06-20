<?php

namespace Cerbero\JsonParser\Exceptions;

use Exception;

/**
 * The exception thrown when a JSON source is not supported.
 *
 */
final class UnsupportedSourceException extends Exception implements JsonParserException
{
    /**
     * @var mixed
     */
    private $source;

    /**
     * Instantiate the class.
     *
     * @param mixed $source
     */
    public function __construct($source)
    {
        $this->source = $source;

        parent::__construct('Unable to load JSON from the provided source');
    }
}
