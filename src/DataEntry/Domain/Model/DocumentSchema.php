<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model;

use Star\Component\Document\Common\Domain\Model\DocumentId;

interface DocumentSchema
{
    /**
     * @return DocumentId
     */
    public function getIdentity(): DocumentId;

    /**
     * @param string $propertyName
     * @param mixed $rawValue
     *
     * @return RecordValue
     */
    public function createValue(string $propertyName, $rawValue): RecordValue;
}
