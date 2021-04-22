<?php


namespace nickdnk\OpenAPI\Tests\Components;


use nickdnk\OpenAPI\OpenAPIDocument;
use nickdnk\OpenAPI\Types\AnArray;
use nickdnk\OpenAPI\Types\AnInteger;
use nickdnk\OpenAPI\Types\AnObject;
use nickdnk\OpenAPI\Types\AString;
use nickdnk\OpenAPI\Types\Base;
use nickdnk\OpenAPI\Types\Property;

class RequestExample2 implements OpenAPIDocument
{

    public static function getOpenAPISpec(?string $forRequestType = null, ?Base ...$withSchemas): Base
    {
        return AnObject::withProperties(
            new Property('some_array_with_strings',
                AnArray::withItems(
                    AString::get()
                        ->withDescription('A string property.')
                        ->withMinLength(1)
                        ->withMaxLength(255)
                )
            ),
            new Property('some_integer',
                AnInteger::get()->withDescription('An integer property.')
            )
        );
    }
}
