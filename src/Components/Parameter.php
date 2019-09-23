<?php


namespace nickdnk\OpenAPI\Components;

use JsonSerializable;
use nickdnk\OpenAPI\Types\Base;

class Parameter implements JsonSerializable
{

    const TYPE_PATH  = 'path';
    const TYPE_QUERY = 'query';

    private $name, $type, $description, $isRequired, $schema;

    /**
     * Parameter constructor.
     *
     * @param $name
     * @param $type
     * @param $description
     * @param $isRequired
     * @param $schema
     */
    private function __construct(string $name, string $type, string $description, bool $isRequired, Base $schema)
    {

        $this->name = $name;
        $this->type = $type;
        $this->description = $description;
        $this->isRequired = $isRequired;
        $this->schema = $schema;
    }

    final public static function query(string $name, string $description, Base $schema, bool $isRequired = false): self
    {

        return new self(
            $name, Parameter::TYPE_QUERY, $description, $isRequired, $schema
        );

    }

    final public static function path(string $name, string $description, Base $schema): self
    {

        return new self(
            $name, Parameter::TYPE_PATH, $description, true, $schema
        );
    }

    /**
     * Specify data which should be serialized to JSON
     *
     * @link  https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
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