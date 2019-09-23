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
     * @param string      $url
     * @param string|null $description
     * @param array       $variables
     */
    public function __construct(string $url, ?string $description, ?array $variables)
    {

        $this->url = $url;
        $this->description = $description;
        $this->variables = $variables;
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