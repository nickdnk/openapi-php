<?php


namespace nickdnk\OpenAPI\Tests\Types;

use nickdnk\OpenAPI\Types\AnArray;
use nickdnk\OpenAPI\Types\AnObject;
use nickdnk\OpenAPI\Types\AString;
use nickdnk\OpenAPI\Types\Property;
use PHPUnit\Framework\TestCase;

class AnArrayTest extends TestCase
{

    public function testJsonEncode()
    {

        $this->assertEquals(
            '{"type":"array","items":{"type":"object","properties":{"test":{"type":"string"}}},"minItems":1,"maxItems":45,"uniqueItems":true}',
            json_encode(
                AnArray::withItems(AnObject::withProperties(new Property('test', AString::get())))
                    ->withMinItems(1)
                    ->withMaxItems(45)
                    ->uniqueItems()
            )
        );

    }

}
