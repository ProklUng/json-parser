<?php

namespace Cerbero\JsonParser\Exceptions;

use Cerbero\JsonParser\Decoders\DecodedValue;
use Exception;

/**
 * The exception thrown when a JSON value cannot be decoded.
 *
 */
final class DecodingException extends Exception implements JsonParserException
{
    /**
     * @var DecodedValue
     */
    public $decoded;

    /**
     * Instantiate the class
     *
     * @param DecodedValue $decoded
     */
    public function __construct(DecodedValue $decoded)
    {
        $this->decoded = $decoded;
        parent::__construct('Decoding error: ' . $decoded->error, (int) $decoded->code);
    }
}
