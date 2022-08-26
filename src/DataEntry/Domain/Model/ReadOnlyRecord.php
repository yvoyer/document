<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model;

use Star\Component\Document\Design\Domain\Model\DocumentTypeId;

interface ReadOnlyRecord
{
    /**
     * @return DocumentId
     */
    public function getIdentity(): DocumentId;

    /**
     * @return DocumentTypeId
     */
    public function getDocumentId(): DocumentTypeId;
}
