<?php


namespace nickdnk\OpenAPI\Types;

use JetBrains\PhpStorm\Pure;

class ANumber extends Base
{

    const FORMAT_FLOAT = 'float';
    const FORMAT_DOUBLE = 'double';

    private ?float $minValue = null, $maxValue = null;

    #[Pure]
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
    public function withExample(mixed $example): static
    {

        $this->example = $example === null ? Base::DEFAULT_NULL : $example;

        return $this;
    }

    public function jsonSerialize(): array
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