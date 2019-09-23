<?php


namespace nickdnk\OpenAPI\Types;

class ABoolean extends Base
{

    public function jsonSerialize()
    {

        $return = parent::jsonSerialize();

        $return['type'] = 'boolean';

        return $return;

    }

    final public static function get()
    {

        return new self();
    }

}