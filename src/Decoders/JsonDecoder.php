<?php

namespace Cerbero\JsonParser\Decoders;

/**
 * The decoder using the built-in JSON decoder.
 *
 */
final class JsonDecoder extends AbstractDecoder
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
     * @param int<1, max> $depth
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
        return json_decode($json, $this->decodesToArray, $this->depth, JSON_THROW_ON_ERROR);
    }
}
