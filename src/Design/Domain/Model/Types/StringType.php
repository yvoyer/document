<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Types;

use Star\Component\Document\DataEntry\Domain\Model\RawValue;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\Design\Domain\Model\Values\EmptyValue;
use Star\Component\Document\Design\Domain\Model\Values\StringValue;
use Star\Component\Document\Design\Domain\Model\PropertyType;

final class StringType implements PropertyType
{
    public function createValue(string $propertyName, RawValue $rawValue): RecordValue
    {
        if ($rawValue->isEmpty()) {
            return new EmptyValue();
        }

        if (!$rawValue->isString()) {
            throw InvalidPropertyValue::invalidValueForType($propertyName, 'string', $rawValue);
        }

        return StringValue::fromString($rawValue->toString());
    }

    public function toData(): TypeData
    {
        return new TypeData(self::class);
    }

    public static function fromData(array $arguments): PropertyType
    {
        return new self();
    }
}
