<?php


namespace nickdnk\OpenAPI\Types;

use JetBrains\PhpStorm\Pure;

class ABoolean extends Base
{

    public function jsonSerialize(): array
    {

        $return = parent::jsonSerialize();

        $return['type'] = 'boolean';

        return $return;

    }

    #[Pure]
    final public static function get(): self
    {

        return new self();
    }

}