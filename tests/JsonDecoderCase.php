<?php

use Cerbero\JsonParser\Decoders\JsonDecoder;
use PHPUnit\Framework\TestCase;

/**
 * Class JsonDecoderCase
 */
class JsonDecoderCase extends TestCase
{
    /**
     * @var string
     */
    private $validJson;

    /**
     * @var string
     */
    private $invalidJson;

    /**
     * @var string
     */
    private $safedJson;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->validJson = json_encode(['test' => 23]);
        $this->safedJson = htmlspecialchars(json_encode(['test' => 23]));
        $this->invalidJson = '"test":1}';
    }

    /**
     * @return void
     */
    public function testDecodeSafedJson() : void
    {
        $obj = new JsonDecoder();

        $result = $obj->decode($this->safedJson);

        $this->assertIsArray($result->value);
        $this->assertNotEmpty($result->value);
        $this->assertEmpty($result->error);
        $this->assertSame(['test' => 23], $result->value);
    }

    /**
     * @return void
     */
    public function testDecodeNormalJson() : void
    {
        $obj = new JsonDecoder();

        $result = $obj->decode($this->validJson);

        $this->assertIsArray($result->value);
        $this->assertNotEmpty($result->value);
        $this->assertEmpty($result->error);
        $this->assertSame(['test' => 23], $result->value);
    }

    /**
     * @return void
     */
    public function testDecodeInvalidJson() : void
    {
        $obj = new JsonDecoder();

        $result = $obj->decode($this->invalidJson);

        $this->assertEmpty($result->value);
        $this->assertNotEmpty($result->error);
        $this->assertNotEmpty($result->exception);
    }
}