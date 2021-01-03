<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Messaging\Query;

use Star\Component\Document\Design\Domain\Model\PropertyType;

final class ReadOnlyPropertyType
{
    /**
     * @var PropertyType
     */
    private $propertyType;

    public function __construct(PropertyType $propertyType)
    {
        $this->propertyType = $propertyType;
    }

    public function toString(): string
    {
        return $this->propertyType->toData()->toString();
    }
}
