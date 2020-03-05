<?php


namespace nickdnk\OpenAPI\Tests\Types;

use nickdnk\OpenAPI\Types\AnInteger;
use PHPUnit\Framework\TestCase;

class AnIntegerTest extends TestCase
{

    public function testJsonEncode()
    {

        $this->assertEquals(
            '{"description":"A description","default":1,"enum":[1,2,3,4],"format":"int32","type":"integer","minimum":2,"maximum":5}',
            json_encode(
                AnInteger::get()
                    ->withFormat(AnInteger::FORMAT_INT_32)
                    ->withMinimum(2)
                    ->withMaximum(5)
                    ->withEnum([1, 2, 3, 4])
                    ->withDescription('A description')
                    ->withDefault(1)
            )
        );

    }

}
