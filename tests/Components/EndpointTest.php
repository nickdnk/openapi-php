<?php


namespace nickdnk\OpenAPI\Tests\Components;

use nickdnk\OpenAPI\Components\Endpoint;
use PHPUnit\Framework\TestCase;


class EndpointTest extends TestCase
{

    public function testEndpointSimpleJson()
    {

        $this->assertEquals(
            '{"tags":[null],"summary":"Test Post Endpoint Single JSON","operationId":"-testPostEndpointSingleJSON","description":"Description of test endpoint","requestBody":{"required":true,"content":{"application\/json":{"schema":{"type":"object","properties":{"some_property_boolean":{"description":"A boolean property.","type":"boolean"},"some_property_string":{"description":"A string property.","type":"string","minLength":1,"maxLength":255}}}}}}}',
            json_encode(
                Endpoint::post(
                    'Test Post Endpoint Single JSON',
                    'Description of test endpoint'
                )->withRequestBodyFromEntity(RequestExample::class)
            )
        );

    }

    public function testEndpointMultipleJson()
    {

        $this->assertEquals(
            '{"tags":[null],"summary":"Test Post Endpoint Multi JSON","operationId":"-testPostEndpointMultiJSON","description":"Test endpoint with multiple JSON bodies.","requestBody":{"required":true,"content":{"application\/json":{"schema":{"oneOf":[{"type":"object","properties":{"some_property_boolean":{"description":"A boolean property.","type":"boolean"},"some_property_string":{"description":"A string property.","type":"string","minLength":1,"maxLength":255}}},{"type":"object","properties":{"some_array_with_strings":{"type":"array","items":{"description":"A string property.","type":"string","minLength":1,"maxLength":255}},"some_integer":{"description":"An integer property.","type":"integer"}}}]}}}}}'
            , json_encode(
                Endpoint::post(
                    'Test Post Endpoint Multi JSON',
                    'Test endpoint with multiple JSON bodies.'
                )->withRequestBodyFromEntity(RequestExample::class)
                    ->withRequestBodyFromEntity(RequestExample2::class)
            )
        );

    }

    public function testEndpointMixedJsonWWWForm()
    {

        $this->assertEquals(
            '{"tags":[null],"summary":"Test Post Endpoint Multi Mixed","operationId":"-testPostEndpointMultiMixed","description":"Test endpoint with mixed JSON and www-form bodies.","requestBody":{"required":true,"content":{"application\/json":{"schema":{"type":"object","properties":{"some_property_boolean":{"description":"A boolean property.","type":"boolean"},"some_property_string":{"description":"A string property.","type":"string","minLength":1,"maxLength":255}}}},"application\/x-www-form-urlencoded":{"schema":{"type":"object","properties":{"some_array_with_strings":{"type":"array","items":{"description":"A string property.","type":"string","minLength":1,"maxLength":255}},"some_integer":{"description":"An integer property.","type":"integer"}}}}}}}',
            json_encode(
                Endpoint::post(
                    'Test Post Endpoint Multi Mixed',
                    'Test endpoint with mixed JSON and www-form bodies.'
                )->withRequestBodyFromEntity(RequestExample::class)
                    ->withRequestBodyFromEntity(RequestExample2::class, Endpoint::CONTENT_TYPE_APPLICATION_X_WWW_FORM_URLENCODED)
            )
        );

    }

}
