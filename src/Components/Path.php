<?php


namespace nickdnk\OpenAPI\Components;

use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;
use JsonSerializable;

class Path implements JsonSerializable
{

    /**
     * @var Endpoint[] $endPoints
     */
    private array $endPoints;

    /** @var Parameter[]|null */
    private ?array $commonParameters;

    private string $path;

    /**
     * Path constructor.
     *
     * @param string $path
     * @param Endpoint[] $endPoints
     */
    private function __construct(string $path, array $endPoints)
    {
        if (count($endPoints) === 0) {
            throw new InvalidArgumentException('Empty path: ' . $path);
        }

        $checkedVerbs = [];

        foreach ($endPoints as $endPoint) {

            if (isset($checkedVerbs[$endPoint->getHttpMethod()])) {
                throw new InvalidArgumentException(
                    'Duplicate method; ' . strtoupper($endPoint->getHttpMethod()) . ' in path: ' . $path
                );
            }

            $checkedVerbs[$endPoint->getHttpMethod()] = true;

        }

        $this->endPoints = $endPoints;
        $this->commonParameters = null;
        $this->path = $path;

    }

    final public static function build(string $path, Endpoint ...$andEndpoints): self
    {

        return new self(
            $path, $andEndpoints
        );

    }

    final public function withCommonParameters(Parameter ...$parameters): self
    {

        $this->commonParameters = $parameters;

        return $this;

    }

    public function jsonSerialize(): array
    {

        $return = [];

        foreach ($this->endPoints as $endPoint) {

            $return[$endPoint->getHttpMethod()] = $endPoint;

        }

        if ($this->commonParameters) {

            $return['parameters'] = [];

            foreach ($this->commonParameters as $parameter) {

                $return['parameters'][] = $parameter;

            }

        }

        return $return;
    }

    #[Pure]
    final public function getPath(): string
    {

        return $this->path;
    }

    /**
     * @return Endpoint[]
     */
    #[Pure]
    public function getEndPoints(): array
    {

        return $this->endPoints;
    }

}