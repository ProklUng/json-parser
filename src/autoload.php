<?php

// @see https://github.com/cerbero90/json-parser

use Cerbero\JsonParser\Decoders\Decoder;

if (interface_exists(Decoder::class)) {
    return;
}

if (\PHP_VERSION_ID < 80000 && !interface_exists(Stringable::class)) {
    interface Stringable
    {
        /**
         * @return string
         */
        public function __toString();
    }
}

require_once dirname(__FILE__) . '/Decoders/Decoder.php';
require_once dirname(__FILE__) . '/Concerns/DetectsEndpoints.php';
require_once dirname(__FILE__) . '/Concerns/GuzzleAware.php';

require_once dirname(__FILE__) . '/Exceptions/JsonParserException.php';
require_once dirname(__FILE__) . '/Exceptions/DecodingException.php';
require_once dirname(__FILE__) . '/Exceptions/GuzzleRequiredException.php';
require_once dirname(__FILE__) . '/Exceptions/IntersectingPointersException.php';
require_once dirname(__FILE__) . '/Exceptions/InvalidPointerException.php';
require_once dirname(__FILE__) . '/Exceptions/SyntaxException.php';
require_once dirname(__FILE__) . '/Exceptions/UnsupportedSourceException.php';

require_once dirname(__FILE__) . '/Decoders/AbstractDecoder.php';
require_once dirname(__FILE__) . '/Decoders/ConfigurableDecoder.php';
require_once dirname(__FILE__) . '/Decoders/DecodedValue.php';
require_once dirname(__FILE__) . '/Decoders/JsonDecoder.php';
require_once dirname(__FILE__) . '/Decoders/SimdjsonDecoder.php';

require_once dirname(__FILE__) . '/Pointers/Pointer.php';
require_once dirname(__FILE__) . '/Pointers/Pointers.php';

require_once dirname(__FILE__) . '/Sources/Source.php';
require_once dirname(__FILE__) . '/Sources/StreamWrapper.php';

require_once dirname(__FILE__) . '/Sources/AnySource.php';
require_once dirname(__FILE__) . '/Sources/CustomSource.php';
require_once dirname(__FILE__) . '/Sources/Endpoint.php';
require_once dirname(__FILE__) . '/Sources/Filename.php';
require_once dirname(__FILE__) . '/Sources/IterableSource.php';
require_once dirname(__FILE__) . '/Sources/Json.php';
require_once dirname(__FILE__) . '/Sources/JsonResource.php';

require_once dirname(__FILE__) . '/Sources/Psr7Message.php';
require_once dirname(__FILE__) . '/Sources/Psr7Request.php';
require_once dirname(__FILE__) . '/Sources/Psr7Stream.php';

require_once dirname(__FILE__) . '/Tokens/Token.php';
require_once dirname(__FILE__) . '/Tokens/Tokenizer.php';
require_once dirname(__FILE__) . '/Tokens/Tokens.php';
require_once dirname(__FILE__) . '/Tokens/Colon.php';
require_once dirname(__FILE__) . '/Tokens/Comma.php';
require_once dirname(__FILE__) . '/Tokens/CompoundBegin.php';
require_once dirname(__FILE__) . '/Tokens/CompoundEnd.php';
require_once dirname(__FILE__) . '/Tokens/Constant.php';
require_once dirname(__FILE__) . '/Tokens/Lexer.php';
require_once dirname(__FILE__) . '/Tokens/Parser.php';
require_once dirname(__FILE__) . '/Tokens/ScalarString.php';

require_once dirname(__FILE__) . '/ValueObjects/Config.php';
require_once dirname(__FILE__) . '/ValueObjects/Progress.php';
require_once dirname(__FILE__) . '/ValueObjects/State.php';
require_once dirname(__FILE__) . '/ValueObjects/Tree.php';

require_once dirname(__FILE__) . '/JsonParser.php';



