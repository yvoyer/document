<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Messaging\Command;

use Star\Component\Document\Design\Domain\Model\DocumentRepository;

final class RemovePropertyConstraintHandler
{
    /**
     * @var DocumentRepository
     */
    private $documents;

    public function __construct(DocumentRepository $documents)
    {
        $this->documents = $documents;
    }

    public function __invoke(RemovePropertyConstraint $command): void
    {
        $document = $this->documents->getDocumentByIdentity($command->documentId());
        $document->removeConstraint($command->name(), $command->constraintName());

        $this->documents->saveDocument($command->documentId(), $document);
    }
}
