<?php


namespace nickdnk\OpenAPI\Types;

use InvalidArgumentException;

class AnObject extends Base
{

    private $properties;

    private function __construct(array $properties)
    {

        $this->properties = $properties;
    }

    final public static function naked(): self
    {

        return new self([]);
    }

    /**
     * @param AnObject $withObject
     *
     * @return AnObject
     */
    final public function merge(AnObject $withObject): self
    {

        $this->properties = array_merge($this->properties, $withObject->getProperties());

        ksort($this->properties);

        return $withObject->getRequired() ? $this->withRequired(
            array_merge($this->getRequired(), $withObject->getRequired())
        ) : $this;
    }

    /**
     * Adds all of the specified properties to the object.
     *
     * You cannot add properties as required here, as the idea is to take all of the
     * properties at once and then subsequently specify which ones are required
     * using the withRequired()- or requireAll()-method.
     *
     * @param Property ...$properties
     *
     * @return AnObject
     */
    final public static function withProperties(Property ...$properties): self
    {

        if (!$properties) {
            throw new InvalidArgumentException('withProperties() requires at least one property.');
        }

        $loop = [];

        foreach ($properties as $property) {

            $loop[$property->getName()] = $property->getSchema();

        }

        ksort(
            $loop,
            SORT_STRING
        );

        return new self($loop);
    }

    /**
     * We override only for phpdoc to match return type.
     *
     * @param string|null $description
     * @param bool        $clone
     *
     * @return AnObject
     */
    public function withDescription(?string $description, $clone = false)
    {

        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return parent::withDescription($description, $clone);
    }

    /**
     * We override only for phpdoc to match return type.
     *
     * @param string $class
     *
     * @return AnObject
     */
    public function withTitleFromClass(string $class)
    {

        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return parent::withTitleFromClass($class);
    }

    /**
     * Adds a property to the object.
     *
     * If the second parameter is true the property will also be added to the array of
     * required properties. If the second parameter is false the property is not added
     * to the required properties, but is also explicitly removed from the array of
     * required properties if already present.
     *
     * By default this method sorts the properties alphabetically, which can be
     * suppressed by passing false to the $sort parameter.
     *
     * @param Property $property
     * @param bool     $required
     * @param bool     $sort
     *
     * @return AnObject
     */
    final public function addProperty(Property $property, $required = false, $sort = true): self
    {

        $this->properties[$property->getName()] = $property->getSchema();

        if ($required) {

            if ($this->getRequired() !== null) {

                $this->withRequired(
                    array_merge(
                        $this->getRequired(),
                        [$property->getName()]
                    )
                );
            } else {

                $this->withRequired([$property->getName()]);
            }

        } else {

            if ($this->getRequired()) {

                if (($key = array_search(
                        $property->getName(),
                        $this->getRequired(),
                        true
                    )) !== false) {
                    unset($this->getRequired()[$key]);
                    $this->withRequired(array_values($this->getRequired()));
                }

            }

        }

        if ($sort) {

            ksort(
                $this->properties,
                SORT_STRING
            );

        }

        return $this;

    }

    /**
     * Removes the property with the specified name from the object.
     * The property is also removed from the array of required properties.
     *
     * @param string $property
     *
     * @return AnObject
     */
    final public function removeProperty(string $property): self
    {

        unset($this->properties[$property]);

        if ($this->getRequired()) {

            if (($key = array_search(
                    $property,
                    $this->getRequired(),
                    true
                )) !== false) {
                unset($this->getRequired()[$key]);
                $this->withRequired(array_values($this->getRequired()));
            }

        }

        return $this;

    }

    /**
     * For all other resources this method takes a boolean, but for objects the
     * 'required' property is an array of strings indicating the keys on the object
     * that are required, so this method overrides that logic and takes an array
     * instead of a string.
     *
     * We use inheritance to explicitly make it impossible to pass a boolean to
     * this resource as this is always a mistake. This is why the type-hint
     * is phpdoc only, as this is overridable and PHP does not allow overriding
     * actual method parameter types in subclasses.
     *
     * @param string[] $requiredProperties
     *
     * @return self
     */
    final public function withRequired(array $requiredProperties)
    {

        if (!is_array($requiredProperties)) {
            throw new InvalidArgumentException(
                'withRequired() for AnObject must be an array of strings. Received: ' . gettype($requiredProperties)
            );
        }

        /** @noinspection PhpParamsInspection */
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return parent::isRequired($requiredProperties);

    }

    /**
     * @param $required
     *
     * @return Base|void
     * @throws InvalidArgumentException
     * @deprecated
     */
    public function isRequired($required = true)
    {

        throw new InvalidArgumentException('AnObject cannot use isRequired. Use withRequired instead.');
    }

    /**
     * Sometimes it's easier to specify that all parameters are required -  with exceptions -
     * than it is to explicitly require all properties.
     *
     * @param string[]|null $except
     *
     * @return AnObject
     */
    final public function requireAll(array $except = null): self
    {

        $this->withRequired(
            $except === null
                ? array_keys($this->properties)
                : array_values(
                array_diff(array_keys($this->properties), $except)
            )
        );

        asort($this->getRequired());

        return $this;

    }

    /**
     * Adds the specified property to the array of required properties of the object.
     *
     * Duplicates are skipped, so if the property is already required this method
     * essentially does nothing.
     *
     * @param string $property
     */
    final public function requireProperty(string $property)
    {

        self::withRequired(
            array_merge(
                parent::getRequired() ?? [],
                [$property]
            )
        );
    }

    /**
     * @return Property[]
     */
    final public function getProperties(): array
    {

        return $this->properties;
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

        $return = parent::jsonSerialize();

        $return['type'] = 'object';
        $return['properties'] = $this->properties;

        return $return;
    }

}