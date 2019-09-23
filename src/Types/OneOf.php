<?php


namespace nickdnk\OpenAPI\Types;

class OneOf extends Base
{

    private $oneOf;

    /**
     * AllOf constructor.
     *
     * @param $oneOf
     */
    private function __construct(array $oneOf)
    {

        $this->oneOf = $oneOf;
    }

    /**
     * @param Base ...$items
     *
     * @return OneOf
     */
    final public static function items(Base ... $items): self
    {

        return new self($items);

    }

    public function jsonSerialize()
    {

        $return = parent::jsonSerialize();

        $return['oneOf'] = $this->oneOf;

        return $return;

    }

}