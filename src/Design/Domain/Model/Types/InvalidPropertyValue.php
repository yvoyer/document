<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Types;

use Star\Component\Document\DataEntry\Domain\Model\RawValue;

final class InvalidPropertyValue extends \InvalidArgumentException
{
    public static function invalidValueForType(string $propertyName, string $type, RawValue $value): self
    {
        return new self(
            \sprintf(
                'The property "%s" expected a "%s" value, "%s" given.',
                $propertyName,
                $type,
                $value->getType()
            )
        );
    }
}
