<?php


namespace nickdnk\OpenAPI\Components;

use JetBrains\PhpStorm\Pure;
use JsonSerializable;

class Contact implements JsonSerializable
{

    private string $url, $name, $email;

    #[Pure]
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