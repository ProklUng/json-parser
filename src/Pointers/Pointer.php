<?php

namespace Cerbero\JsonParser\Pointers;

use Cerbero\JsonParser\Exceptions\InvalidPointerException;
use Cerbero\JsonParser\ValueObjects\Tree;
use Closure;
use Stringable;

use function count;
use function is_int;
use function array_slice;

/**
 * The JSON pointer.
 *
 */
final class Pointer implements Stringable
{
    /**
     * The reference tokens.
     *
     * @var string[]
     */
    public $referenceTokens = [];

    /**
     * The pointer depth.
     *
     * @var int $depth
     */
    public $depth;

    /**
     * Whether the pointer was found.
     *
     * @var bool $wasFound
     */
    public $wasFound = false;

    /**
     * @var Closure|null $callback
     */
    private $callback;

    /**
     * @var string $pointer
     */
    private $pointer;

    /**
     * @var bool $isLazy
     */
    public $isLazy;

    /**
     * Instantiate the class.
     *
     * @param string       $pointer
     * @param bool         $isLazy
     * @param Closure|null $callback
     *
     * @throws InvalidPointerException
     */
    public function __construct(
        string $pointer,
        bool $isLazy = false,
        ?Closure $callback = null
    ) {
        $this->pointer = $pointer;
        $this->isLazy = $isLazy;
        $this->callback = $callback;

        $this->referenceTokens = $this->toReferenceTokens();
        $this->depth = count($this->referenceTokens);
    }

    /**
     * Turn the JSON pointer into reference tokens.
     *
     * @return string[]
     * @throws InvalidPointerException
     */
    private function toReferenceTokens(): array
    {
        if (preg_match('#^(?:/(?:(?:[^/~])|(?:~[01]))*)*$#', $this->pointer) === 0) {
            throw new InvalidPointerException($this->pointer);
        }

        $tokens = explode('/', $this->pointer);
        $referenceTokens = array_map(fn (string $token) => str_replace(['~1', '~0'], ['/', '~'], $token), $tokens);

        return array_slice($referenceTokens, 1);
    }

    /**
     * Call the pointer callback.
     *
     * @param mixed $value
     * @param mixed $key
     *
     * @return mixed
     */
    public function call($value, &$key)
    {
        if ($this->callback === null) {
            return $value;
        }

        return ($this->callback)($value, $key) ?? $value;
    }

    /**
     * Determine whether the reference token at the given depth matches the provided key.
     *
     * @param int        $depth
     * @param string|int $key
     *
     * @return bool
     */
    public function depthMatchesKey(int $depth, $key): bool
    {
        $referenceToken = $this->referenceTokens[$depth] ?? null;

        return $referenceToken === (string) $key
            || (is_int($key) && $referenceToken === '-');
    }

    /**
     * Determine whether the pointer matches the given tree
     *
     * @param Tree $tree
     * @return bool
     */
    public function matchesTree(Tree $tree): bool
    {
        return $this->referenceTokens == []
            || $this->referenceTokens == $tree->original()
            || $this->referenceTokens == $tree->wildcarded();
    }

    /**
     * Determine whether the pointer includes the given tree
     *
     * @param Tree $tree
     * @return bool
     */
    public function includesTree(Tree $tree): bool
    {
        if ($this->pointer == '') {
            return true;
        }

        return is_int($firstNest = array_search('-', $this->referenceTokens))
            && array_slice($this->referenceTokens, 0, $firstNest) === array_slice($tree->original(), 0, $firstNest);
    }

    /**
     * Retrieve the underlying JSON pointer
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->pointer;
    }
}
