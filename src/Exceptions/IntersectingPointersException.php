<?php

namespace Cerbero\JsonParser\Exceptions;

use Cerbero\JsonParser\Pointers\Pointer;
use Exception;

/**
 * The exception thrown when two JSON pointers intersect.
 *
 */
class IntersectingPointersException extends Exception implements JsonParserException
{
    /**
     * @var Pointer
     */
    public $pointer1;

    /**
     * @var Pointer
     */
    public $pointer2;

    /**
     * Instantiate the class.
     *
     * @param Pointer $pointer1
     * @param Pointer $pointer2
     */
    public function __construct(Pointer $pointer1, Pointer $pointer2)
    {
        $this->pointer1 = $pointer1;
        $this->pointer2 = $pointer2;

        parent::__construct("The pointers [$pointer1] and [$pointer2] are intersecting");
    }
}
