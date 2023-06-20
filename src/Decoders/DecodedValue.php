<?php

namespace Cerbero\JsonParser\Decoders;

use Throwable;

/**
 * The decoded value.
 *
 */
final class DecodedValue
{
    /**
     * @var mixed $value
     */
    public $value;
    
    /**
     * @var bool $succeeded
     */
    public $succeeded;

    /**
     * @var string|null $error
     */
    public $error;

    /**
     * @var int|null $code
     */
    public $code;

    /**
     * @var Throwable|null $exception
     */
    public $exception;

    /**
     * @var string|null $json
     */
    public $json;

    /**
     * Retrieve a successfully decoded value.
     *
     * @param mixed $value
     *
     * @return self
     */
    public static function succeeded($value): self
    {
        return new self(true, $value);
    }

    /**
     * Retrieve a value failed to be decoded.
     *
     * @param Throwable $e
     * @param string    $json
     *
     * @return self
     */
    public static function failed(Throwable $e, string $json): self
    {
        return new self(false, null, $e->getMessage(), $e->getCode(), $e, $json);
    }

    /**
     * Instantiate the class.
     *
     * @param mixed $value
     */
    private function __construct(
        bool $succeeded,
        $value = null,
        ?string $error = null,
        ?int $code = null,
        ?Throwable $exception = null,
        ?string $json = null
    ) {
        $this->value = $value;
        $this->succeeded = $succeeded;
        $this->error = $error;
        $this->code = $code;
        $this->exception = $exception;
        $this->json = $json;
    }
}
