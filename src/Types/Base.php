<?php


namespace nickdnk\OpenAPI\Types;

use JetBrains\PhpStorm\Pure;
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

    private bool|array|null $required = null;
    private ?string $description = null;
    private ?bool $nullable = null;

    private mixed $default = null;
    private mixed $enum = null;
    protected mixed $example = null;

    private ?string $format = null, $title = null, $deprecated = null;

    final public function isNullable(bool $nullable = true): static
    {

        if ($nullable === null) {
            return $this;
        }

        $this->nullable = $nullable;

        return $this;
    }

    final public function isDeprecated(bool $deprecated = true): static
    {

        $this->deprecated = $deprecated;

        return $this;

    }

    #[Pure]
    final public function cloned(): static
    {

        return clone($this);
    }

    public function withDescription(?string $description, bool $clone = false): static
    {

        $ref = $clone ? clone($this) : $this;

        $ref->description = $description;

        return $ref;

    }

    final public function withFormat(string $format): static
    {

        $this->format = $format;

        return $this;

    }

    final public function withEnum(array $enum): static
    {

        $this->enum = $enum;

        return $this;
    }

    final public function withTitle(string $title): static
    {

        $this->title = $title;

        return $this;
    }

    public function withTitleFromClass(string $class): static
    {

        /** @noinspection PhpUnhandledExceptionInspection */
        return $this->withTitle((new ReflectionClass($class))->getShortName());
    }

    public function isRequired(bool|array $required = true): static
    {

        $this->required = $required;

        return $this;

    }

    public function withDefault(mixed $default): static
    {

        $this->default = $default === null ? Base::DEFAULT_NULL : $default;

        return $this;

    }

    public function withExample(mixed $example): static
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
     * @return string[]|bool|null
     */
    public function &getRequired(): array|bool|null
    {

        return $this->required;
    }


    public function jsonSerialize(): array
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

        if ($this->deprecated) {
            $return['deprecated'] = true;
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