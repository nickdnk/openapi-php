<?php


namespace nickdnk\OpenAPI\Components;

use JsonSerializable;

class License implements JsonSerializable
{

    private $name, $url;

    public function __construct(string $name, ?string $url)
    {

        $this->name = $name;
        $this->url = $url;
    }

    public function jsonSerialize(): array
    {

        $return = [
            'name' => $this->name
        ];

        if ($this->url) {
            $return['url'] = $this->url;
        }

        return $return;
    }
}