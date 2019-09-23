<?php


namespace nickdnk\OpenAPI;

use nickdnk\OpenAPI\Types\Base;

interface OpenAPIDocument
{

    /**
     * Entities or objects that implement this method for documentation must return their property structure
     * using the Components available in this library. Override the return method of this phpdoc to the actual
     * object returned (which must be a child of Base) to suppress warnings.
     *
     * Use the first parameter to tell the object if its properties should reflect a specific HTTP method (as input) or
     * simply its properties when used as output (passing null). This allows you to re-use the same entity/object for
     * operations such as GET/PUT of the same resource.
     *
     * Use the second parameter to tell the object of other objects it may contain or should change its structure
     * depending on. E.g. a list object that can contain any type of object/type in its list-property.
     *
     * @param string|null $forRequestType
     * @param Base|null   ...$withSchemas
     *
     * @return Base
     */
    public static function getOpenAPISpec(?string $forRequestType = null, ?Base ...$withSchemas): Base;

}