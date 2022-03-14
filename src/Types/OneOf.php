<?php


namespace nickdnk\OpenAPI\Types;

use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;

class OneOf extends Base
{

    private array $oneOf;

    private function __construct(array $oneOf)
    {

        $this->oneOf = $oneOf;
    }

    #[Pure]
    final public static function items(Base ...$items): self
    {

        return new self($items);

    }

    final public function getObjectAtIndex(int $index): Base
    {

        if (isset($this->oneOf[$index])) {
            return $this->oneOf[$index];
        }

        throw new InvalidArgumentException('Index ' . $index . ' not defined on oneOf.');
    }

    public function jsonSerialize(): array
    {

        $return = parent::jsonSerialize();

        $return['oneOf'] = $this->oneOf;

        return $return;

    }

}