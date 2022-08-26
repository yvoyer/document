<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Types;

use InvalidArgumentException;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\Design\Domain\Model\PropertyCode;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use function sprintf;

final class InvalidPropertyValue extends InvalidArgumentException
{
    public static function invalidValueForType(
        PropertyCode $property,
        RecordValue $value,
        PropertyType $type
    ): self {
        return new self(
            sprintf(
                'The property "%s" expected a "%s" value, "%s" given.',
                $property->toString(),
                $type->toHumanReadableString(),
                $value->toTypedString()
            )
        );
    }
}
