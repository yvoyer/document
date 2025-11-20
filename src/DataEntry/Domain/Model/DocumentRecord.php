<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model;

use Star\Component\Document\DataEntry\Domain\Model\Validation\StrategyToHandleValidationErrors;

interface DocumentRecord extends ReadOnlyRecord
{
    /**
     * @param string $code
     * @param RecordValue $value
     * @param StrategyToHandleValidationErrors|null $strategy
     */
    public function setValue(
        string $code,
        RecordValue $value,
        StrategyToHandleValidationErrors $strategy = null
    ): void;

    /**
     * @param string $code
     *
     * @return RecordValue
     */
    public function getValue(string $code): RecordValue;
}
