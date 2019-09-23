<?php


namespace nickdnk\OpenAPI\Types;

class AnyOf extends Base
{

    private $anyOf;

    /**
     * AllOf constructor.
     *
     * @param $anyOf
     */
    private function __construct(array $anyOf)
    {

        $this->anyOf = $anyOf;
    }

    /**
     * @param Base ...$items
     *
     * @return AnyOf
     */
    final public static function items(Base... $items): self
    {

        return new self($items);

    }

    public function jsonSerialize()
    {

        $return = parent::jsonSerialize();

        $return['anyOf'] = $this->anyOf;

        return $return;

    }

}