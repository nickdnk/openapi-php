<?php


namespace nickdnk\OpenAPI\Types;

use InvalidArgumentException;

class AnyOf extends Base
{

    private $anyOf;

    private function __construct(array $anyOf)
    {

        $this->anyOf = $anyOf;
    }

    /**
     * @param Base ...$items
     *
     * @return AnyOf
     */
    final public static function items(Base...$items): self
    {

        return new self($items);

    }

    final public function getObjectAtIndex(int $index)
    {

        if (isset($this->anyOf[$index])) {
            return $this->anyOf[$index];
        }

        throw new InvalidArgumentException('Index ' . $index . ' not defined on anyOf.');
    }

    public function jsonSerialize(): array
    {

        $return = parent::jsonSerialize();

        $return['anyOf'] = $this->anyOf;

        return $return;

    }

}