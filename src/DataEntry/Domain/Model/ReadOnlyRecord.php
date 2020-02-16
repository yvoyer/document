<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model;

use Star\Component\Document\Design\Domain\Model\DocumentId;

interface ReadOnlyRecord
{
    /**
     * @return RecordId
     */
    public function getIdentity(): RecordId;

    /**
     * @return DocumentId
     */
    public function getDocumentId(): DocumentId;
}
