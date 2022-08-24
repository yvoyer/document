<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Messaging\Command;

use Star\Component\Document\Design\Domain\Model\DocumentTypeRepository;

final class RemovePropertyConstraintHandler
{
    private DocumentTypeRepository $documents;

    public function __construct(DocumentTypeRepository $documents)
    {
        $this->documents = $documents;
    }

    public function __invoke(RemovePropertyConstraint $command): void
    {
        $document = $this->documents->getDocumentByIdentity($command->documentId());
        $document->removePropertyConstraint($command->code(), $command->constraintName());

        $this->documents->saveDocument($document);
    }
}
