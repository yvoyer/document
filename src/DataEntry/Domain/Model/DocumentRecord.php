<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model;

use Star\Component\Document\DataEntry\Domain\Model\Validation\StrategyToHandleValidationErrors;

interface DocumentRecord extends ReadOnlyRecord
{
    /**
     * @param string $propertyName
     * @param RecordValue $value
     * @param StrategyToHandleValidationErrors|null $strategy
     */
    public function setValue(
        string $propertyName,
        RecordValue $value,
        StrategyToHandleValidationErrors $strategy = null
    ): void;

    /**
     * @param string $propertyName
     *
     * @return RecordValue
     */
    public function getValue(string $propertyName): RecordValue;
}
