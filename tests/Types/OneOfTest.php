<?php


namespace nickdnk\OpenAPI\Tests\Types;

use InvalidArgumentException;
use nickdnk\OpenAPI\Types\AnInteger;
use nickdnk\OpenAPI\Types\AString;
use nickdnk\OpenAPI\Types\OneOf;
use PHPUnit\Framework\TestCase;

class OneOfTest extends TestCase
{


    public function testGetObjectAtIndex()
    {

        $string = AString::get();
        $int = AnInteger::get();

        $oneOf = OneOf::items($string, $int);

        $this->assertEquals($string, $oneOf->getObjectAtIndex(0));

    }

    public function testGetObjectAtIndexInvalid()
    {

        $this->expectException(InvalidArgumentException::class);

        $string = AString::get();
        $int = AnInteger::get();

        $oneOf = OneOf::items($string, $int);

        $this->assertEquals($string, $oneOf->getObjectAtIndex(5));

    }
}
