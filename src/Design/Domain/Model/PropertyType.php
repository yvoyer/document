<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

use Star\Component\Document\DataEntry\Domain\Model\RawValue;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\Design\Domain\Model\Types\TypeData;

interface PropertyType
{
    /**
     * @param string $propertyName // todo Replace to PropertyName
     * @param RawValue  $rawValue
     *
     * @return RecordValue
     */
    public function createValue(string $propertyName, RawValue $rawValue): RecordValue;

    public function toData(): TypeData;

    public function toString(): string;

    /**
     * @param mixed[] $arguments
     * @return PropertyType
     */
    public static function fromData(array $arguments): PropertyType;
}
