<?php


namespace nickdnk\OpenAPI\Types;

use JetBrains\PhpStorm\Pure;

class Property
{

    private Base $schema;
    private string $name;

    public function __construct(string $name, Base $schema)
    {

        $this->name = $name;
        $this->schema = $schema;
    }

    #[Pure]
    final public function getName(): string
    {

        return $this->name;
    }

    #[Pure]
    final public function getSchema(): Base
    {

        return $this->schema;
    }

}