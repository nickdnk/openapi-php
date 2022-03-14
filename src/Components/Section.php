<?php


namespace nickdnk\OpenAPI\Components;

use JetBrains\PhpStorm\Pure;
use JsonSerializable;

class Section implements JsonSerializable
{

    private array $paths;
    private string $description, $name;

    /**
     * @param string $name
     * @param string $description
     * @param Path[] $paths
     */
    #[Pure]
    private function __construct(string $name, string $description, array $paths)
    {

        $this->name = $name;
        $this->description = $description;
        $this->paths = $paths;
    }

    #[Pure]
    final public static function build(string $name, string $andDescription, Path ...$paths): self
    {

        return new self(
            $name, $andDescription, $paths
        );
    }

    #[Pure]
    final public function getName(): string
    {

        return $this->name;
    }

    /**
     * @return Path[]
     */
    #[Pure]
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