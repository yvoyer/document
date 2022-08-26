<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

use Star\Component\Identity\Exception\EntityNotFoundException;

interface DocumentTypeRepository
{
    /**
     * @param DocumentTypeId $id
     *
     * @return DocumentTypeAggregate
     * @throws EntityNotFoundException
     */
    public function getDocumentByIdentity(DocumentTypeId $id): DocumentTypeAggregate;

    /**
     * @param DocumentTypeAggregate $document
     */
    public function saveDocument(DocumentTypeAggregate $document): void;
}
