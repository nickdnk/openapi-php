<?php


namespace nickdnk\OpenAPI\Tests\Types;

use InvalidArgumentException;
use nickdnk\OpenAPI\Types\AnInteger;
use nickdnk\OpenAPI\Types\AnObject;
use nickdnk\OpenAPI\Types\AString;
use nickdnk\OpenAPI\Types\Property;
use PHPUnit\Framework\TestCase;

class AnObjectTest extends TestCase
{

    public function testWithProperties()
    {

        $string = AString::get();

        $property = new Property('test_property', $string);

        $object = AnObject::withProperties($property);

        $this->assertEquals($string, $object->getProperties()['test_property']);

    }

    public function testWithPropertiesEmptyArray()
    {

        $this->expectException(InvalidArgumentException::class);

        AnObject::withProperties();

    }

    public function testNaked()
    {

        $object = AnObject::naked();

        $this->assertEmpty($object->getProperties());

    }

    public function testRequireAll()
    {

        $string = AString::get();
        $int = AnInteger::get();

        $object = AnObject::withProperties(
            new Property('test_property_string', $string),
            new Property('test_property_int', $int)
        )
            ->requireAll(['test_property_string']);

        $this->assertEquals(['test_property_int'], $object->getRequired());

        $object->requireAll();

        $this->assertEquals(['test_property_int', 'test_property_string'], $object->getRequired());

    }

    public function testWithRequired()
    {

        $string = AString::get();
        $int = AnInteger::get();

        $object = AnObject::withProperties(
            new Property('test_property_string', $string),
            new Property('test_property_int', $int)
        )
            ->withRequired(['test_property_string']);

        $this->assertEquals(['test_property_string'], $object->getRequired());

    }

    public function testIsRequired()
    {

        $this->expectException(InvalidArgumentException::class);

        AnObject::withProperties(
            new Property('test_property_string', AString::get())
        )
            ->isRequired();

    }

    public function testAddProperty()
    {

        $object = AnObject::withProperties(
            new Property('test_property_root', AString::get())
        );

        $object->addProperty(new Property('test_added_property_optional', AString::get()));
        $object->addProperty(new Property('test_added_property_required', AString::get()), true);

        $this->assertArrayHasKey('test_added_property_optional', $object->getProperties());
        $this->assertArrayHasKey('test_added_property_required', $object->getProperties());

        $this->assertEquals(['test_added_property_required'], $object->getRequired());

    }

    public function testRemoveProperty()
    {

        $object = AnObject::withProperties(
            new Property('test_property_string', AString::get()), // wrong alphabetical order to test sort
            new Property('test_property_int', AnInteger::get()),
            new Property('test_property_to_remove', AnInteger::get())
        )
            ->requireAll();

        $object->removeProperty('test_property_to_remove');

        $this->assertArrayNotHasKey('test_property_to_remove', $object->getProperties());

        $this->assertEquals(['test_property_int', 'test_property_string'], $object->getRequired());

    }

    public function testRequireProperty()
    {

        $object = AnObject::withProperties(
            new Property('test_property_string', AString::get()),
            new Property('test_property_int', AnInteger::get())
        );

        $object->requireProperty('test_property_string');

        $this->assertEquals(['test_property_string'], $object->getRequired());

    }

    public function testMerge()
    {

        $object1 = AnObject::withProperties(new Property('test_property_string', AString::get()));
        $object2 = AnObject::withProperties(new Property('test_property_int', AnInteger::get()));

        $object1->merge($object2);

        $this->assertArrayHasKey('test_property_string', $object1->getProperties());
        $this->assertArrayHasKey('test_property_int', $object1->getProperties());

        $this->assertArrayNotHasKey('test_property_string', $object2->getProperties());

    }

    public function testJsonEncode()
    {

        $this->assertEquals(
            '{"description":"Object description","required":["test"],"type":"object","properties":{"test":{"type":"string"}}}',
            json_encode(
                AnObject::withProperties(new Property('test', AString::get()))
                    ->withDescription('Object description')
                    ->withRequired(['test'])
            )
        );

    }

    public function testDeprecated()
    {

        $this->assertEquals(
            '{"deprecated":true,"type":"object","properties":{"test":{"deprecated":true,"type":"string"}}}',
            json_encode(
                AnObject::withProperties(new Property('test', AString::get()->isDeprecated()))
                    ->isDeprecated()
            )
        );

    }

    public function testMergeNoRequiredWithRequired()
    {

        $obj = AnObject::withProperties(new Property('test', AString::get()));

        $obj->merge(AnObject::withProperties(new Property('required_prop', AString::get()))->requireAll());

        $this->assertEquals(
            '{"required":["required_prop"],"type":"object","properties":{"required_prop":{"type":"string"},"test":{"type":"string"}}}',
            json_encode(
                $obj
            )
        );

    }
}
