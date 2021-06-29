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
     * @param Path ...$paths
     *
     * @return Section
     */
    final public static function build(string $name, string $andDescription, ...$paths): self
    {

        return new self(
            $name, $andDescription, $paths
        );
    }

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

    public function jsonSerialize(): array
    {

        return [
            'name'        => $this->name,
            'description' => $this->description
        ];
    }
}