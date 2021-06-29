<?php


namespace nickdnk\OpenAPI\Types;

class ABoolean extends Base
{

    public function jsonSerialize(): array
    {

        $return = parent::jsonSerialize();

        $return['type'] = 'boolean';

        return $return;

    }

    final public static function get(): self
    {

        return new self();
    }

}