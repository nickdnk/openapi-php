<?php


namespace nickdnk\OpenAPI\Types;

use InvalidArgumentException;
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

    /**
     * @expectedException InvalidArgumentException
     */
    public function testGetObjectAtIndexInvalid()
    {

        $string = AString::get();
        $int = AnInteger::get();

        $oneOf = OneOf::items($string, $int);

        $this->assertEquals($string, $oneOf->getObjectAtIndex(5));

    }
}
