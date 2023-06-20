<?php

namespace Cerbero\JsonParser\Tokens;

use Cerbero\JsonParser\Decoders\ConfigurableDecoder;
use Cerbero\JsonParser\Exceptions\SyntaxException;
use Cerbero\JsonParser\Tokens\CompoundBegin;
use Cerbero\JsonParser\Tokens\CompoundEnd;
use Cerbero\JsonParser\Tokens\Token;
use Cerbero\JsonParser\ValueObjects\Config;
use Cerbero\JsonParser\ValueObjects\State;
use Generator;
use IteratorAggregate;
use Traversable;

/**
 * The JSON parser.
 *
 * @implements IteratorAggregate<string|int, mixed>
 */
final class Parser implements IteratorAggregate
{
    /**
     * The decoder handling potential errors.
     *
     * @var ConfigurableDecoder $decoder
     */
    private $decoder;

    /**
     * Whether the parser is fast-forwarding.
     *
     * @var bool $isFastForwarding
     */
    private $isFastForwarding = false;

    /**
     * @var Config $config
     */
    private $config;

    /**
     * @var Generator $tokens
     */
    private $tokens;

    /**
     * Instantiate the class.
     *
     * @param Generator<int, Token> $tokens
     * @param Config $config
     */
    public function __construct(Generator $tokens, Config $config)
    {
        $this->tokens = $tokens;
        $this->config = $config;

        $this->decoder = new ConfigurableDecoder($config);
    }

    /**
     * @inheritDoc
     */
    public function getIterator(): Traversable
    {
        $state = new State($this->config->pointers, fn () => new self($this->lazyLoad(), clone $this->config));

        foreach ($this->tokens as $token) {
            if ($this->isFastForwarding) {
                continue;
            } elseif (!$token->matches($state->expectedToken)) {
                throw new SyntaxException($token);
            }

            $state->mutateByToken($token);

            if (!$token->endsChunk() || $state->tree->isDeep()) {
                continue;
            }

            if ($state->hasBuffer()) {
                /** @var string|int $key */
                $key = $this->decoder->decode($state->tree->currentKey());
                $value = $this->decoder->decode($state->value());

                yield $key => $state->callPointer($value, $key);

                $value instanceof self && $value->fastForward();
            }

            if ($state->canStopParsing()) {
                break;
            }
        }
    }

    /**
     * Retrieve the generator to lazy load the current compound.
     *
     * @return Generator<int, Token>
     */
    public function lazyLoad(): Generator
    {
        $depth = 0;

        do {
            yield $token = $this->tokens->current();

            if ($token instanceof CompoundBegin) {
                $depth++;
            } elseif ($token instanceof CompoundEnd) {
                $depth--;
            }

            $depth > 0 && $this->tokens->next();
        } while ($depth > 0);
    }

    /**
     * Eager load the current compound into an array.
     *
     * @return array<string|int, mixed>
     * @throws SyntaxException
     */
    public function toArray(): array
    {
        $array = [];

        foreach ($this as $key => $value) {
            $array[$key] = $value instanceof self ? $value->toArray() : $value;
        }

        return $array;
    }

    /**
     * Fast-forward the parser.
     *
     * @return void
     */
    public function fastForward(): void
    {
        if (!$this->tokens->valid()) {
            return;
        }

        $this->isFastForwarding = true;

        foreach ($this as $value) {
            $value instanceof self && $value->fastForward(); // @codeCoverageIgnore
        }
    }
}
