<?php


namespace nickdnk\OpenAPI\Types;

class AllOf extends Base
{

    private $allOf;

    /**
     * AllOf constructor.
     *
     * @param $allOf
     */
    private function __construct(array $allOf)
    {

        $this->allOf = $allOf;
    }

    /**
     * @param Base ...$items
     *
     * @return AllOf
     */
    final public static function items(Base ...$items): self
    {

        return new self($items);

    }

    /**
     * Removes the property with the specified name from all objects within the allOf-array.
     *
     * @param string $property
     *
     * @return AllOf
     */
    final public function removeProperty(string $property): self
    {

        foreach ($this->allOf as &$item) {

            if ($item instanceof AnObject) {

                $item->removeProperty($property);

            }

        }
        unset($item);

        return $this;

    }

    public function jsonSerialize()
    {

        $return = parent::jsonSerialize();

        $return['allOf'] = $this->allOf;

        return $return;

    }

}