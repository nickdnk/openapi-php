<?php


namespace nickdnk\OpenAPI\Types;

use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;

class AnyOf extends Base
{

    /** @var Base[] */
    private array $anyOf;

    #[Pure]
    private function __construct(array $anyOf)
    {

        $this->anyOf = $anyOf;
    }

    #[Pure]
    final public static function items(Base...$items): self
    {

        return new self($items);

    }

    final public function getObjectAtIndex(int $index): Base
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