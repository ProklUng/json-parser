<?php

namespace Cerbero\JsonParser\Tokens;

use Cerbero\JsonParser\Exceptions\SyntaxException;
use Cerbero\JsonParser\Sources\Source;
use Cerbero\JsonParser\ValueObjects\Progress;
use IteratorAggregate;
use Traversable;

use function strlen;

/**
 * The JSON lexer.
 *
 * @implements IteratorAggregate<int, Token>
 */
final class Lexer implements IteratorAggregate
{
    /**
     * The parsing progress.
     *
     * @var Progress $progress
     */
    private $progress;

    /**
     * The current position.
     *
     * @var int $position
     */
    private $position = 0;

    /**
     * @var Source
     */
    private $source;

    /**
     * Instantiate the class.
     *
     * @param Source $source
     */
    public function __construct(Source $source)
    {
        $this->source = $source;
        $this->progress = new Progress();
    }

    /**
     * @inheritDoc
     */
    public function getIterator(): Traversable
    {
        $buffer = '';
        $inString = $isEscaping = false;
        $tokenizer = Tokenizer::instance();

        foreach ($this->source as $chunk) {
            for ($i = 0, $size = strlen($chunk); $i < $size; $i++, $this->position++) {
                $character = $chunk[$i];
                $inString = ($character == '"') != $inString || $isEscaping;
                $isEscaping = $character == '\\' && !$isEscaping;

                if ($inString || !isset(Tokens::BOUNDARIES[$character])) {
                    if ($buffer == '' && !isset(Tokens::TYPES[$character])) {
                        throw new SyntaxException($character);
                    }

                    $buffer .= $character;
                    continue;
                }

                if ($buffer != '') {
                    yield $tokenizer->toToken($buffer);
                    $buffer = '';
                }

                if (isset(Tokens::DELIMITERS[$character])) {
                    yield $tokenizer->toToken($character);
                }
            }
        }
    }

    /**
     * Retrieve the current position
     *
     * @return int
     */
    public function position(): int
    {
        return $this->position;
    }

    /**
     * Retrieve the parsing progress
     *
     * @return Progress
     */
    public function progress(): Progress
    {
        return $this->progress->setCurrent($this->position)->setTotal($this->source->size());
    }
}
