<?php


namespace nickdnk\OpenAPI\Components;

use JsonSerializable;

class ServerVariable implements JsonSerializable
{

    private $enum, $default, $description;

    /**
     * ServerVariable constructor.
     *
     * @param $enum
     * @param $default
     * @param $description
     */
    public function __construct(array $enum, string $default, ?string $description)
    {

        $this->enum = $enum;
        $this->default = $default;
        $this->description = $description;
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