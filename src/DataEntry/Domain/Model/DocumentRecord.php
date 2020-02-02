<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model;

use Star\Component\Document\DataEntry\Domain\Model\Validation\StrategyToHandleValidationErrors;

interface DocumentRecord extends ReadOnlyRecord
{
    /**
     * @param string $propertyName
     * @param mixed $rawValue
     * @param StrategyToHandleValidationErrors $strategy
     */
    public function setValue(string $propertyName, $rawValue, StrategyToHandleValidationErrors $strategy): void;

    /**
     * @param string $propertyName
     *
     * @return RecordValue
     */
    public function getValue(string $propertyName): RecordValue;
}
