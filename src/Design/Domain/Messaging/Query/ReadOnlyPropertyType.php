<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Messaging\Query;

use Star\Component\Document\Design\Domain\Model\PropertyType;

final class ReadOnlyPropertyType
{
    /**
     * @var string
     */
    private $label;

    /**
     * @var PropertyType
     */
    private $propertyType;

    public function __construct(string $label, PropertyType $propertyType)
    {
        $this->label = $label;
        $this->propertyType = $propertyType;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getType(): string
    {
        return $this->propertyType->toHumanReadableString();
    }

    public function toString(): string
    {
        return $this->propertyType->toData()->toString();
    }
}
