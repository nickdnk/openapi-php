<?php


namespace nickdnk\OpenAPI\Types;

class Property
{

    private $name, $schema;

    /**
     * AProperty constructor.
     *
     * @param $name
     * @param $schema
     */
    public function __construct(string $name, Base $schema)
    {

        $this->name = $name;
        $this->schema = $schema;
    }

    /**
     * @return string
     */
    final public function getName(): string
    {

        return $this->name;
    }

    /**
     * @return Base
     */
    final public function getSchema(): Base
    {

        return $this->schema;
    }

}