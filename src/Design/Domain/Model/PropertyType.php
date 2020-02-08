<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\Design\Domain\Model\Types\TypeData;

interface PropertyType
{
    public function validateRawValue(string $propertyName, $rawValue): ErrorList;

    /**
     * @param string $propertyName // todo Replace to PropertyName
     * @param mixed $rawValue
     *
     * @return RecordValue
     */
    public function createValue(string $propertyName, $rawValue): RecordValue;

    public function toData(): TypeData;

    public static function fromData(array $arguments): PropertyType;
}
