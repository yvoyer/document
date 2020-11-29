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
     * @param string $property
     * @param RecordValue $value
     * @return NotSupportedTypeForValue
     */
    public function generateExceptionForNotSupportedTypeForValue(
        string $property,
        RecordValue $value
    ): NotSupportedTypeForValue;

    /**
     * @param string $property
     * @param RecordValue $value
     * @return InvalidPropertyValue
     */
    public function generateExceptionForNotSupportedValue(
        string $property,
        RecordValue $value
    ): InvalidPropertyValue;
}
