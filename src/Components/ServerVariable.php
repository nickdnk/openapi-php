<?php


namespace nickdnk\OpenAPI\Components;

use JsonSerializable;

class ServerVariable implements JsonSerializable
{

    private $enum, $default, $description;

    public function __construct(array $enum, string $default, ?string $description)
    {

        $this->enum = $enum;
        $this->default = $default;
        $this->description = $description;
    }

    public function jsonSerialize(): array
    {

        $return = [
            'default' => $this->default
        ];

        if ($this->enum) {
            $return['enum'] = $this->enum;
        }

        if ($this->description) {
            $return['description'] = $this->description;
        }

        return $return;
    }
}