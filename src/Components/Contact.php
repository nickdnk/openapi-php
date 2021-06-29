<?php


namespace nickdnk\OpenAPI\Components;

use JsonSerializable;

class Contact implements JsonSerializable
{

    private $name, $url, $email;

    public function __construct(string $name, string $url, string $email)
    {

        $this->name = $name;
        $this->url = $url;
        $this->email = $email;
    }

    public function jsonSerialize(): array
    {

        return [
            'name'  => $this->name,
            'url'   => $this->url,
            'email' => $this->email
        ];
    }
}