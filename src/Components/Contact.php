<?php


namespace nickdnk\OpenAPI\Components;

use JsonSerializable;

class Contact implements JsonSerializable
{

    private $name, $url, $email;

    /**
     * Contact constructor.
     *
     * @param string $name
     * @param string $url
     * @param string $email
     */
    public function __construct(string $name, string $url, string $email)
    {

        $this->name = $name;
        $this->url = $url;
        $this->email = $email;
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
            'name'  => $this->name,
            'url'   => $this->url,
            'email' => $this->email
        ];
    }
}