<?php

namespace Cerbero\JsonParser\Decoders;

use Cerbero\JsonParser\Tokens\Parser;
use Cerbero\JsonParser\ValueObjects\Config;

/**
 * The configurable decoder.
 *
 */
final class ConfigurableDecoder
{
    /**
     * @var Config $config
     */
    private $config;

    /**
     * Instantiate the class.
     *
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Decode the given value.
     *
     * @param Parser|string|int $value
     *
     * @return mixed
     */
    public function decode($value)
    {
        if (!is_string($value)) {
            return $value;
        }

        $decoded = $this->config->decoder->decode($value);

        if (!$decoded->succeeded) {
            ($this->config->onDecodingError)($decoded);
        }

        return $decoded->value;
    }
}
