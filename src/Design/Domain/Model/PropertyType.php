<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

use Star\Component\Document\DataEntry\Domain\Model\PropertyCode;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\Design\Domain\Model\Types\InvalidPropertyValue;
use Star\Component\Document\Design\Domain\Model\Types\NotSupportedTypeForValue;
use Star\Component\Document\Design\Domain\Model\Types\TypeData;

interface PropertyType
{
    /**
     * Convert the $value to a write-only format
     *
     * @param RecordValue $value
     * @return RecordValue
     */
    public function toWriteFormat(RecordValue $value): RecordValue;

    /**
     * Convert the $value to a read-only format
     *
     * @param RecordValue $value
     * @return RecordValue
     */
    public function toReadFormat(RecordValue $value): RecordValue;

    /**
     * Whether the type of the value is supported by the PropertyType
     * @param RecordValue $value
     * @return bool
     */
    public function supportsType(RecordValue $value): bool;

    /**
     * Whether the content of the RecordValue is supported by the PropertyType
     *
     * @param RecordValue $value
     * @return bool
     */
    public function supportsValue(RecordValue $value): bool;

    public function generateExceptionForNotSupportedTypeForValue(
        PropertyCode $property,
        RecordValue $value
    ): NotSupportedTypeForValue;

    public function generateExceptionForNotSupportedValue(
        PropertyCode $property,
        RecordValue $value
    ): InvalidPropertyValue;

    public function doBehavior(string $property, RecordValue $value): RecordValue;

    public function toData(): TypeData;

    /**
     * @return string The human-readable name of the type
     */
    public function toHumanReadableString(): string;

    /**
     * @param mixed[] $arguments
     * @return PropertyType
     */
    public static function fromData(array $arguments): PropertyType;
}
