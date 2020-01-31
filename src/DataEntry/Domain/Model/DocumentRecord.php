<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model;

interface DocumentRecord extends ReadOnlyRecord
{
    /**
     * @param string $propertyName
     * @param mixed $rawValue
     */
    public function setValue(string $propertyName, $rawValue): void;

    /**
     * @param string $propertyName
     *
     * @return RecordValue
     */
    public function getValue(string $propertyName): RecordValue;
}
