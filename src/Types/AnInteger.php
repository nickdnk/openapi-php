<?php


namespace nickdnk\OpenAPI\Types;

class AnInteger extends Base
{

    const FORMAT_INT_32 = 'int32';
    const FORMAT_INT_64 = 'int64';

    private $minValue, $maxValue;

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

    final public function withMinimum(int $minValue): self
    {

        $this->minValue = $minValue;

        return $this;

    }

    final public function withMaximum(int $maxValue): self
    {

        $this->maxValue = $maxValue;

        return $this;

    }

    /**
     * @param int|null $example
     *
     * @return AnInteger
     */
    public function withExample($example): self
    {

        $this->example = $example === null ? Base::DEFAULT_NULL : $example;

        return $this;
    }

    public function jsonSerialize(): array
    {

        $return = parent::jsonSerialize();

        $return['type'] = 'integer';

        if ($this->minValue !== null) {
            $return['minimum'] = $this->minValue;
        }

        if ($this->maxValue !== null) {
            $return['maximum'] = $this->maxValue;
        }

        return $return;

    }
}