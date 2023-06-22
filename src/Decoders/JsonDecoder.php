<?php

namespace Cerbero\JsonParser\Decoders;

use JsonException;

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
        // Частные моменты - "обезопашенные" json из базы.
        $result = null;
        try {
            $result = json_decode($this->htmlspecialcharsback($json), $this->decodesToArray, $this->depth, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {}


        if (is_array($result) || is_object($result)) {
            return $result;
        }

        return json_decode($json, $this->decodesToArray, $this->depth, JSON_THROW_ON_ERROR);
    }

    /**
     * @param mixed $str
     *
     * @return mixed
     */
    private function htmlspecialcharsback($str)
    {
        static $search =  array("&lt;", "&gt;", "&quot;", "&apos;", "&amp;");
        static $replace = array("<",    ">",    "\"",     "'",      "&");

        return self::str_replace($search, $replace, $str);
    }

    /**
     * Compatible with php 8 for nested arrays. Only the first level of the array is processed.
     *
     * @param mixed $search
     * @param mixed $replace
     * @param mixed $str
     *
     * @return mixed
     */
    private static function str_replace($search, $replace, $str)
    {
        if (is_array($str))
        {
            foreach ($str as $key => $value)
            {
                if (is_scalar($value))
                {
                    $str[$key] = str_replace($search, $replace, $value);
                }
            }
        }
        else
        {
            $str = str_replace($search, $replace, $str);
        }

        return $str;
    }
}
