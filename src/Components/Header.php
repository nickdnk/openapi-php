<?php


namespace nickdnk\OpenAPI\Components;

use JsonSerializable;
use nickdnk\OpenAPI\Types\Base;

class Header implements JsonSerializable
{

    const LOCATION = 'Location';

    private $header, $value;
    /**
     * @var string
     */
    private $description;

    /**
     * Header constructor.
     *
     * @param string $header
     * @param string $description
     * @param Base   $value
     */
    public function __construct(string $header, string $description, Base $value)
    {

        $this->header = $header;
        $this->value = $value;
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getHeader(): string
    {

        return $this->header;
    }

    /**
     * @return Base
     */
    public function getValue(): Base
    {

        return $this->value;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {

        return $this->description;
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

        return [
            'schema'      => $this->getValue(),
            'description' => $this->getDescription()
        ];
    }
}