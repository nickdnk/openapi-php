<?php


namespace nickdnk\OpenAPI\Types;

class AString extends Base
{

    const FORMAT_BYTE      = 'byte';
    const FORMAT_BINARY    = 'binary';
    const FORMAT_DATE      = 'date';
    const FORMAT_DATE_TIME = 'date-time';
    const FORMAT_PASSWORD  = 'password';

    private $maxLength, $minLength, $pattern;

    /**
     * AString constructor.
     */
    private function __construct()
    {
    }

    final public static function get(): self
    {

        return new self();
    }

    final public static function naked(): self
    {

        return new self();
    }

    final public function withMinLength(int $minLength): self
    {

        $this->minLength = $minLength;

        return $this;

    }

    final public function withMaxLength(int $maxLength): self
    {

        $this->maxLength = $maxLength;

        return $this;

    }

    /**
     * @param string|null $example
     *
     * @return AString
     */
    public function withExample($example): self
    {

        $this->example = $example === null ? Base::DEFAULT_NULL : $example;

        return $this;
    }

    final public function withPattern(string $pattern): self
    {

        $this->pattern = $pattern;

        return $this;
    }

    public function jsonSerialize(): array
    {

        $return = parent::jsonSerialize();

        $return['type'] = 'string';

        if ($this->minLength !== null) {
            $return['minLength'] = $this->minLength;
        }

        if ($this->maxLength !== null) {
            $return['maxLength'] = $this->maxLength;
        }

        if ($this->pattern !== null) {
            $return['pattern'] = $this->pattern;
        }

        return $return;
    }
}