<?php

namespace Cerbero\JsonParser\Tokens;

use Cerbero\JsonParser\ValueObjects\State;

/**
 * The scalar string token.
 *
 */
final class ScalarString extends Token
{
    /**
     * Whether this token is an object key.
     *
     * @var bool $isKey
     */
    private $isKey = false;

    /**
     * Mutate the given state.
     *
     * @param State $state
     *
     * @return void
     */
    public function mutateState(State $state): void
    {
        if ($this->isKey = $state->expectsKey) {
            $state->expectsKey = false;
            $state->expectedToken = Tokens::COLON;
            return;
        }

        $state->expectedToken = $state->tree->inObject() ? Tokens::AFTER_OBJECT_VALUE : Tokens::AFTER_ARRAY_VALUE;
    }

    /**
     * @inheritDoc
     */
    public function endsChunk(): bool
    {
        return !$this->isKey;
    }
}
