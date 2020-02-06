<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

use Star\Component\Document\DataEntry\Domain\Model\RecordValue;

interface PropertyType
{
    /**
     * @param string $propertyName // todo Replace to PropertyName
     * @param mixed $rawValue
     *
     * @return RecordValue
     */
    public function createValue(string $propertyName, $rawValue): RecordValue;

    /**
     * @return string
     */
    public function toString(): string;
}
