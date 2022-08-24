<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model;

use Star\Component\Document\Design\Domain\Model\DocumentTypeId;

interface ReadOnlyRecord
{
    /**
     * @return RecordId
     */
    public function getIdentity(): RecordId;

    /**
     * @return DocumentTypeId
     */
    public function getDocumentId(): DocumentTypeId;
}
