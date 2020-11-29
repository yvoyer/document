<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

use Star\Component\Identity\Exception\EntityNotFoundException;

interface DocumentRepository
{
    /**
     * @param DocumentId $id
     *
     * @return DocumentAggregate
     * @throws EntityNotFoundException
     */
    public function getDocumentByIdentity(DocumentId $id): DocumentAggregate;

    /**
     * @param DocumentAggregate $document
     */
    public function saveDocument(DocumentAggregate $document): void;
}
