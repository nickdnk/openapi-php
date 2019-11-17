<?php


namespace nickdnk\OpenAPI\Types;

use InvalidArgumentException;

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

    final public function getObjectAtIndex(int $index)
    {

        if (isset($this->oneOf[$index])) {
            return $this->oneOf[$index];
        }

        throw new InvalidArgumentException('Index ' . $index . ' not defined on oneOf.');
    }

    public function jsonSerialize()
    {

        $return = parent::jsonSerialize();

        $return['oneOf'] = $this->oneOf;

        return $return;

    }

}