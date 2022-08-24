<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model;

use Star\Component\Document\Design\Domain\Model\Types\InvalidPropertyValue;
use Star\Component\Document\Design\Domain\Model\Types\NotSupportedTypeForValue;

interface PropertyMetadata
{
    /**
     * @return string The typed representation as a string
     */
    public function toTypedString(): string;

    /**
     * Allow execution of a transformation prior to the setting of the value
     *
     * @param RecordValue $value
     * @return RecordValue
     */
    public function toWriteFormat(RecordValue $value): RecordValue;

    /**
     * @param RecordValue $value
     * @return RecordValue
     */
    public function toReadFormat(RecordValue $value): RecordValue;

    /**
     * @param RecordValue $value
     * @return RecordValue
     */
    public function doBehavior(RecordValue $value): RecordValue;

    /**
     * @param RecordValue $value
     * @return bool
     */
    public function supportsType(RecordValue $value): bool;

    /**
     * @param RecordValue $value
     * @return bool
     */
    public function supportsValue(RecordValue $value): bool;

    /**
     * @param PropertyCode $property
     * @param RecordValue $value
     * @return NotSupportedTypeForValue
     */
    public function generateExceptionForNotSupportedTypeForValue(
        PropertyCode $property,
        RecordValue $value
    ): NotSupportedTypeForValue;

    /**
     * @param PropertyCode $property
     * @param RecordValue $value
     * @return InvalidPropertyValue
     */
    public function generateExceptionForNotSupportedValue(
        PropertyCode $property,
        RecordValue $value
    ): InvalidPropertyValue;
}
