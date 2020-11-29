<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Types;

use InvalidArgumentException;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\Design\Domain\Model\PropertyType;

final class NotSupportedTypeForValue extends InvalidArgumentException
{
    public function __construct(
        string $propertyName,
        RecordValue $value,
        PropertyType $type
    ) {
        parent::__construct(
            sprintf(
                'The property "%s" only supports values of type "%s", "%s" given.',
                $propertyName,
                $type->toHumanReadableString(),
                $value->toTypedString()
            )
        );
    }
}
