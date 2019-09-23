<?php


namespace nickdnk\OpenAPI\Types;

class AnArray extends Base
{

    private $items, $minItems, $maxItems, $uniqueItems;

    private function __construct($items)
    {

        $this->items = $items;
    }

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
    final public function withExample($example): self
    {

        $this->example = $example === null ? Base::DEFAULT_NULL : $example;

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
    final public function jsonSerialize()
    {

        $return = parent::jsonSerialize();

        $return['type'] = 'array';

        if ($this->items) {
            $return['items'] = $this->items;
        }

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