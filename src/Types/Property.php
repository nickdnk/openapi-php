<?php


namespace nickdnk\OpenAPI\Types;

class Property
{

    private $name, $schema;

    public function __construct(string $name, Base $schema)
    {

        $this->name = $name;
        $this->schema = $schema;
    }

    final public function getName(): string
    {

        return $this->name;
    }

    final public function getSchema(): Base
    {

        return $this->schema;
    }

}