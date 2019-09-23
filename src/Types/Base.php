<?php


namespace nickdnk\OpenAPI\Types;

use JsonSerializable;
use ReflectionClass;

abstract class Base implements JsonSerializable
{

    /**
     * Because null can be a default value, we have to use this placeholder to tell PHP
     * to actually print out the value, as null is normally skipped entirely. We use
     * this funky notation to avoid ever clashing with an actual string.
     */
    const DEFAULT_NULL = '!-null-!';

    private $required, $description, $nullable, $default, $enum, $format, $title;

    protected $example;

    /**
     * @param bool $nullable
     *
     * @return static
     */
    final public function isNullable(?bool $nullable = true)
    {

        if ($nullable === null) {
            return $this;
        }

        $this->nullable = $nullable;

        return $this;
    }

    /**
     * @return static
     */
    final public function cloned()
    {

        return clone($this);
    }

    /**
     * @param string $description
     *
     * @param bool   $clone
     *
     * @return static
     */
    public function withDescription(?string $description, $clone = false)
    {

        $ref = $clone ? clone($this) : $this;

        $ref->description = $description;

        return $ref;

    }

    /**
     * @param string $format
     *
     * @return static
     */
    final public function withFormat(string $format)
    {

        $this->format = $format;

        return $this;

    }

    /**
     * @param $enum
     *
     * @return static
     */
    final public function withEnum(array $enum)
    {

        $this->enum = $enum;

        return $this;
    }

    /**
     * @param $title
     *
     * @return static
     */
    final public function withTitle($title)
    {

        $this->title = $title;

        return $this;
    }

    /**
     * @noinspection PhpDocMissingThrowsInspection
     *
     * @param string $class
     *
     * @return static
     */
    public function withTitleFromClass(string $class)
    {

        /** @noinspection PhpUnhandledExceptionInspection */
        return $this->withTitle((new ReflectionClass($class))->getShortName());
    }

    /**
     * @param $required
     *
     * @return static
     */
    public function isRequired($required = true)
    {

        $this->required = $required;

        return $this;

    }

    /**
     * @param $default
     *
     * @return static
     */
    public function withDefault($default)
    {

        $this->default = $default === null ? Base::DEFAULT_NULL : $default;

        return $this;

    }

    public function withExample($example)
    {

        $this->example = $example === null ? Base::DEFAULT_NULL : $example;

        return $this;
    }

    /**
     * For objects this returns an array of strings denoting the required properties of the object.
     * In any other case it returns a boolean indicating whether or not the caller is a required property.
     * We return this value by reference in order to be able to use the asort() method on it without creating
     * unnecessary copies of the array.
     *
     * @return mixed
     */
    public function &getRequired()
    {

        return $this->required;
    }


    public function jsonSerialize()
    {

        $return = [];

        if ($this->description) {
            $return['description'] = $this->description;
        }

        if ($this->required) {
            $return['required'] = $this->required;
        }

        if ($this->nullable) {
            $return['nullable'] = true;
        }

        if ($this->default !== null) {
            $return['default'] = $this->default === self::DEFAULT_NULL ? null : $this->default;
        }

        if ($this->example !== null) {
            $return['example'] = $this->example === self::DEFAULT_NULL ? null : $this->example;
        }

        if ($this->enum !== null) {
            $return['enum'] = $this->enum;
        }

        if ($this->format !== null) {
            $return['format'] = $this->format;
        }

        if ($this->title !== null) {
            $return['title'] = $this->title;
        }

        return $return;
    }
}