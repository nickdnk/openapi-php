<?php


namespace nickdnk\OpenAPI\Tests\Components;


use nickdnk\OpenAPI\OpenAPIDocument;
use nickdnk\OpenAPI\Types\ABoolean;
use nickdnk\OpenAPI\Types\AnObject;
use nickdnk\OpenAPI\Types\AString;
use nickdnk\OpenAPI\Types\Base;
use nickdnk\OpenAPI\Types\Property;

class RequestExample implements OpenAPIDocument
{

    public static function getOpenAPISpec(?string $forRequestType = null, ?Base ...$withSchemas): Base
    {
        return AnObject::withProperties(
            new Property('some_property_string',
                AString::get()
                    ->withDescription('A string property.')
                    ->withMinLength(1)
                    ->withMaxLength(255)
            ),
            new Property('some_property_boolean',
                ABoolean::get()->withDescription('A boolean property.')
            )
        );
    }
}
