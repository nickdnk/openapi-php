<?php


namespace nickdnk\OpenAPI\Tests\Types;

use nickdnk\OpenAPI\Types\AString;
use PHPUnit\Framework\TestCase;

class AStringTest extends TestCase
{

    public function testJsonEncode()
    {

        $this->assertEquals(
            '{"description":"A description","default":"2012-01-01","enum":["2012-01-01","2012-01-02"],"format":"date","type":"string","minLength":2,"maxLength":5,"pattern":"\/pattern\/"}',
            json_encode(
                AString::get()
                    ->withFormat(AString::FORMAT_DATE)
                    ->withMinLength(2)
                    ->withMaxLength(5)
                    ->withPattern('/pattern/')
                    ->withEnum(['2012-01-01', '2012-01-02'])
                    ->withDescription('A description')
                    ->withDefault('2012-01-01')
            )
        );

    }

}
