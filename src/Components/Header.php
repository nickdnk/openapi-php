<?php


namespace nickdnk\OpenAPI\Components;

use JsonSerializable;
use nickdnk\OpenAPI\Types\Base;

class Header implements JsonSerializable
{

    const LOCATION = 'Location';

    private $header, $value, $description;

    public function __construct(string $header, string $description, Base $value)
    {

        $this->header = $header;
        $this->value = $value;
        $this->description = $description;
    }

    public function getHeader(): string
    {

        return $this->header;
    }

    public function jsonSerialize(): array
    {

        return [
            'schema'      => $this->value,
            'description' => $this->description
        ];
    }
}