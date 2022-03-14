<?php


namespace nickdnk\OpenAPI\Types;

use JetBrains\PhpStorm\Pure;

class AnArray extends Base
{

    private Base $items;
    private ?int $minItems, $maxItems;
    private ?bool $uniqueItems;

    #[Pure]
    private function __construct($items)
    {

        $this->minItems = null;
        $this->maxItems = null;
        $this->uniqueItems = false;
        $this->items = $items;
    }

    #[Pure]
    final public static function naked(): self
    {

        return new self([]);
    }

    /**
     * Defines which objects the array consists of. AnArray can only
     * hold one type of objects (as in most strongly-typed languages),
     * even though PHP technically supports holding different types
     * in the same array.
     *
     * @param $items Base
     *
     * @return AnArray
     */
    #[Pure]
    final public static function withItems(Base $items): self
    {

        return new self($items);

    }

    final public function withMinItems(int $minItems): self
    {

        $this->minItems = $minItems;

        return $this;
    }

    final public function withMaxItems(int $maxItems): self
    {

        $this->maxItems = $maxItems;

        return $this;
    }

    final public function uniqueItems(): self
    {

        $this->uniqueItems = true;

        return $this;
    }

    /**
     * @param array|null $example
     *
     * @return AnArray
     */
    final public function withExample($example): static
    {

        $this->example = $example === null ? Base::DEFAULT_NULL : $example;

        return $this;
    }

    final public function jsonSerialize(): array
    {

        $return = parent::jsonSerialize();
        $return['type'] = 'array';
        $return['items'] = $this->items;

        if ($this->minItems !== null) {
            $return['minItems'] = $this->minItems;
        }

        if ($this->maxItems !== null) {
            $return['maxItems'] = $this->maxItems;
        }

        if ($this->uniqueItems === true) {
            $return['uniqueItems'] = true;
        }

        return $return;
    }
}