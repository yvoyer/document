<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model;

use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\DataEntry\Domain\Model\Validation\StrategyToHandleValidationErrors;

interface DocumentSchema
{
    /**
     * @return DocumentId
     */
    public function getIdentity(): DocumentId;

    /**
     * @param string $propertyName
     * @param mixed $rawValue
     * @param StrategyToHandleValidationErrors $strategy
     * @return RecordValue
     */
    public function createValue(
        string $propertyName,
        $rawValue,
        StrategyToHandleValidationErrors $strategy
    ): RecordValue;
}
