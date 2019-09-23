<?php


namespace nickdnk\OpenAPI\Components;

use JsonSerializable;

class Section implements JsonSerializable
{

    private $name, $description, $paths;

    /**
     * Section constructor.
     *
     * @param string $name
     * @param string $description
     * @param Path[] $paths
     */
    private function __construct(string $name, string $description, array $paths)
    {

        $this->name = $name;
        $this->description = $description;
        $this->paths = $paths;
    }

    /**
     * @param string $name
     * @param string $andDescription
     * @param Path   ...$paths
     *
     * @return Section
     */
    final public static function build(string $name, string $andDescription, ...$paths): self
    {

        return new self(
            $name, $andDescription, $paths
        );
    }


    /**
     * @return string
     */
    final public function getName(): string
    {

        return $this->name;
    }

    /**
     * @return Path[]
     */
    final public function getPaths(): array
    {

        return $this->paths;
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

        return [
            'name'        => $this->name,
            'description' => $this->description
        ];
    }
}