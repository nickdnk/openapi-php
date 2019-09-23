<?php


namespace nickdnk\OpenAPI\Types;

class ANumber extends Base
{

    const FORMAT_FLOAT  = 'float';
    const FORMAT_DOUBLE = 'double';

    private $minValue, $maxValue;


    private function __construct()
    {
    }

    final public static function get(): self
    {

        return new self();
    }

    final public function withMinimum(float $minValue): self
    {

        $this->minValue = $minValue;

        return $this;

    }

    final public function withMaximum(float $maxValue): self
    {

        $this->maxValue = $maxValue;

        return $this;

    }

    /**
     * @param float|null $example
     *
     * @return ANumber
     */
    public function withExample($example): self
    {

        $this->example = $example === null ? Base::DEFAULT_NULL : $example;

        return $this;
    }

    public function jsonSerialize()
    {

        $return = parent::jsonSerialize();

        $return['type'] = 'number';

        if ($this->minValue !== null) {
            $return['minimum'] = $this->minValue;
        }

        if ($this->maxValue !== null) {
            $return['maximum'] = $this->maxValue;
        }

        return $return;

    }

}