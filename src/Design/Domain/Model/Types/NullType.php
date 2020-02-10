<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Types;

use Star\Component\Document\DataEntry\Domain\Model\RawValue;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\Document\Design\Domain\Model\Values\EmptyValue;

final class NullType implements PropertyType
{
    public function createValue(string $propertyName, RawValue $rawValue): RecordValue
    {
        if (! $rawValue->isEmpty()) {
            throw InvalidPropertyValue::invalidValueForType($propertyName, $this->toString(), $rawValue);
        }

        return new EmptyValue();
    }

    public function toData(): TypeData
    {
        return new TypeData(self::class);
    }

    public function toString(): string
    {
        return 'null';
    }

    public static function fromData(array $arguments): PropertyType
    {
        return new self();
    }
}
