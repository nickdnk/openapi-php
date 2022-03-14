<?php


namespace nickdnk\OpenAPI\Components;

use JetBrains\PhpStorm\Pure;
use JsonSerializable;

class License implements JsonSerializable
{

    private ?string $url;
    private string $name;

    #[Pure]
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