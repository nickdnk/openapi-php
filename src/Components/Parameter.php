<?php


namespace nickdnk\OpenAPI\Components;

use JetBrains\PhpStorm\Pure;
use JsonSerializable;
use nickdnk\OpenAPI\Types\Base;

class Parameter implements JsonSerializable
{

    const TYPE_PATH  = 'path';
    const TYPE_QUERY = 'query';

    private Base $schema;
    private bool $isRequired;
    private string $description, $type, $name;

    #[Pure]
    private function __construct(string $name, string $type, string $description, bool $isRequired, Base $schema)
    {

        $this->name = $name;
        $this->type = $type;
        $this->description = $description;
        $this->isRequired = $isRequired;
        $this->schema = $schema;
    }

    #[Pure]
    final public static function query(string $name, string $description, Base $schema, bool $isRequired = false): self
    {

        return new self(
            $name, Parameter::TYPE_QUERY, $description, $isRequired, $schema
        );

    }

    #[Pure]
    final public static function path(string $name, string $description, Base $schema): self
    {

        return new self(
            $name, Parameter::TYPE_PATH, $description, true, $schema
        );
    }

    public function jsonSerialize(): array
    {

        $return = [
            'name'        => $this->name,
            'in'          => $this->type,
            'description' => $this->description,
            'schema'      => $this->schema
        ];

        if ($this->isRequired) {
            $return['required'] = true;
        }

        return $return;
    }
}