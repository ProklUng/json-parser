<?php

namespace Cerbero\JsonParser\Sources;

use Cerbero\JsonParser\ValueObjects\Config;
use Iterator;
use IteratorAggregate;
use Traversable;

/**
 * The JSON source.
 *
 * @implements IteratorAggregate<int, string>
 */
abstract class Source implements IteratorAggregate
{
    /**
     * The cached size of the JSON source.
     *
     * @var int|null
     */
    protected $size;

    /**
     * Whether the JSON size has already been calculated.
     * Avoid re-calculations when the size is NULL (not computable).
     *
     * @var bool
     */
    protected $sizeWasSet = false;

    /**
     * @var mixed | Iterator
     */
    protected $source;

    /**
     * @var Config
     */
    protected $config;

    /**
     * Retrieve the JSON fragments
     *
     * @return Traversable<int, string>
     */
    abstract public function getIterator(): Traversable;

    /**
     * Determine whether the JSON source can be handled
     *
     * @return bool
     */
    abstract public function matches(): bool;

    /**
     * Retrieve the calculated size of the JSON source
     *
     * @return int|null
     */
    abstract protected function calculateSize(): ?int;

    /**
     * Instantiate the class.
     *
     * @param mixed $source
     * @param Config $config
     */
    final public function __construct(
        $source,
        Config $config = null
    ) {
        $this->source = $source;
        $this->config = $config ?? new Config();
    }

    /**
     * Retrieve the size of the JSON source and cache it
     *
     * @return int|null
     */
    public function size(): ?int
    {
        if (!$this->sizeWasSet) {
            $this->size = $this->calculateSize();
            $this->sizeWasSet = true;
        }

        return $this->size;
    }
}
