<?php


namespace nickdnk\OpenAPI\Tests\Types;

use nickdnk\OpenAPI\Types\ANumber;
use PHPUnit\Framework\TestCase;

class ANumberTest extends TestCase
{

    public function testJsonEncode()
    {

        $this->assertEquals(
            '{"description":"A description","default":1,"enum":[1,2,3,4],"format":"float","type":"number","minimum":2,"maximum":5}',
            json_encode(
                ANumber::get()
                    ->withFormat(ANumber::FORMAT_FLOAT)
                    ->withMinimum(2)
                    ->withMaximum(5)
                    ->withEnum([1, 2, 3, 4])
                    ->withDescription('A description')
                    ->withDefault(1)
            )
        );

    }
}
