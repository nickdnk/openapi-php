<?php


namespace nickdnk\OpenAPI\Components;

use JsonSerializable;

class ExternalDocs implements JsonSerializable
{

    private $url, $description;

    /**
     * ExternalDocs constructor.
     *
     * @param string      $url
     * @param string|null $description
     */
    public function __construct(string $url, ?string $description)
    {

        $this->url = $url;
        $this->description = $description;
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

        return $return;
    }
}