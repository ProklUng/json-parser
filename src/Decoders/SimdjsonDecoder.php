<?php

namespace Cerbero\JsonParser\Decoders;

/**
 * The decoder using the simdjson extension.
 *
 */
final class SimdjsonDecoder extends AbstractDecoder
{
    /**
     * @var bool $decodesToArray
     */
    private $decodesToArray;

    /**
     * @var int $depth
     */
    private $depth;

    /**
     * Instantiate the class.
     *
     * @param bool $decodesToArray
     * @param int $depth
     */
    public function __construct(bool $decodesToArray = true, int $depth = 512)
    {
        $this->decodesToArray = $decodesToArray;
        $this->depth = $depth;
    }

    /**
     * @inheritDoc
     */
    protected function decodeJson(string $json)
    {
        return simdjson_decode($json, $this->decodesToArray, $this->depth);
    }
}
