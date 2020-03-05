<?php


namespace nickdnk\OpenAPI\Tests\Types;

use nickdnk\OpenAPI\Types\ABoolean;
use PHPUnit\Framework\TestCase;

class ABooleanTest extends TestCase
{

    public function testJsonEncode()
    {

        $this->assertEquals(
            '{"description":"Boolean description","default":false,"type":"boolean"}',
            json_encode(
                ABoolean::get()
                    ->withDefault(false)
                    ->withDescription('Boolean description')
            )
        );

    }
}
