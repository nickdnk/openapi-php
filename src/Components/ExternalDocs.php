<?php


namespace nickdnk\OpenAPI\Components;

use JsonSerializable;

class ExternalDocs implements JsonSerializable
{

    private $url, $description;

    public function __construct(string $url, ?string $description)
    {

        $this->url = $url;
        $this->description = $description;
    }

    public function jsonSerialize(): array
    {

        $return = [
            'url' => $this->url
        ];

        if ($this->description) {
            $return['description'] = $this->description;
        }

        return $return;
    }
}