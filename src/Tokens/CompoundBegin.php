<?php

namespace Cerbero\JsonParser\Tokens;

use Cerbero\JsonParser\ValueObjects\State;

/**
 * The token that begins compound data (JSON arrays or objects).
 *
 */
final class CompoundBegin extends Token
{
    /**
     * Whether this compound should be lazy loaded.
     *
     * @var bool $shouldLazyLoad
     */
    public bool $shouldLazyLoad = false;

    /**
     * @inheritDoc
     */
    public function mutateState(State $state): void
    {
        if ($this->shouldLazyLoad = $this->shouldLazyLoad && $state->tree->depth() >= 0) {
            $state->expectedToken = $state->tree->inObject() ? Tokens::AFTER_OBJECT_VALUE : Tokens::AFTER_ARRAY_VALUE;
            return;
        }

        $state->expectsKey = $beginsObject = $this->value == '{';
        $state->expectedToken = $beginsObject ? Tokens::AFTER_OBJECT_BEGIN : Tokens::AFTER_ARRAY_BEGIN;
        $state->tree->deepen($beginsObject);
    }

    /**
     * @inheritDoc
     */
    public function setValue(string $value)
    {
        $this->shouldLazyLoad = false;

        return parent::setValue($value);
    }

    /**
     * @inheritDoc
     */
    public function endsChunk(): bool
    {
        return $this->shouldLazyLoad;
    }
}
