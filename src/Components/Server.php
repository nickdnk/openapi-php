<?php


namespace nickdnk\OpenAPI\Components;

use JsonSerializable;

class Server implements JsonSerializable
{

    private $url, $description, $variables;

    /**
     * Server constructor.
     *
     * Variables must be a [key => value] array with key being the name of a variable and value being a ServerVariable
     * object that describes the variable.
     *
     * @param string $url
     * @param string|null $description
     * @param array|null $variables
     */
    public function __construct(string $url, ?string $description = null, ?array $variables = null)
    {

        $this->url = $url;
        $this->description = $description;
        $this->variables = $variables;
    }

    public function jsonSerialize(): array
    {

        $return = [
            'url' => $this->url
        ];

        if ($this->description) {
            $return['description'] = $this->description;
        }

        if ($this->variables) {
            $return['variables'] = $this->variables;
        }

        return $return;
    }
}