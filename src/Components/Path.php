<?php


namespace nickdnk\OpenAPI\Components;

use InvalidArgumentException;
use JsonSerializable;

class Path implements JsonSerializable
{

    /**
     * @var Endpoint[] $endPoints
     */
    private $endPoints, $commonParameters;
    /**
     * @var string
     */
    private $path;

    /**
     * Path constructor.
     *
     * @param string           $path
     * @param Endpoint[]       $endPoints
     * @param Parameter[]|null $commonParameters
     */
    private function __construct(string $path, array $endPoints, ?array $commonParameters = null)
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
        $this->commonParameters = $commonParameters;
        $this->path = $path;

    }

    /**
     * @param string   $path
     * @param Endpoint ...$andEndpoints
     *
     * @return Path
     */
    final public static function build(string $path, Endpoint ...$andEndpoints): self
    {

        return new self(
            $path, $andEndpoints
        );

    }

    /**
     * @param Parameter ...$parameters
     *
     * @return Path
     */
    final public function withCommonParameters(Parameter ...$parameters): self
    {

        $this->commonParameters = $parameters;

        return $this;

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

    /**
     * @return string
     */
    final public function getPath(): string
    {

        return $this->path;
    }

    /**
     * @return Endpoint[]
     */
    public function getEndPoints(): array
    {

        return $this->endPoints;
    }


}