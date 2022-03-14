<?php


namespace nickdnk\OpenAPI\Components;

use JetBrains\PhpStorm\Pure;
use JsonSerializable;

class ExternalDocs implements JsonSerializable
{

    private ?string $description;
    private string $url;

    #[Pure]
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