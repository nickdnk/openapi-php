<?php


namespace nickdnk\OpenAPI\Components;

use JsonSerializable;

class License implements JsonSerializable
{

    private $name, $url;

    /**
     * License constructor.
     *
     * @param string      $name
     * @param string|null $url
     */
    public function __construct(string $name, ?string $url)
    {

        $this->name = $name;
        $this->url = $url;
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
            'name' => $this->name
        ];

        if ($this->url) {
            $return['url'] = $this->url;
        }

        return $return;
    }
}