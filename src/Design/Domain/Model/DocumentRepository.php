<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Identity\Exception\EntityNotFoundException;

interface DocumentRepository
{
    /**
     * @param DocumentId $id
     *
     * @return DocumentDesigner
     * @throws EntityNotFoundException
     */
    public function getDocumentByIdentity(DocumentId $id): DocumentDesigner;

    /**
     * @param DocumentId $id
     * @param DocumentDesigner $document
     */
    public function saveDocument(DocumentId $id, DocumentDesigner $document);
}
