<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Messaging\Command;

use Star\Component\Document\Design\Domain\Model\DocumentRepository;

final class AddPropertyConstraintHandler
{
    /**
     * @var DocumentRepository
     */
    private $documents;

    /**
     * @param DocumentRepository $documents
     */
    public function __construct(DocumentRepository $documents)
    {
        $this->documents = $documents;
    }

    public function __invoke(AddPropertyConstraint $command): void
    {
        $document = $this->documents->getDocumentByIdentity($command->documentId());
        $document->addPropertyConstraint($command->name(), $command->constraintName(), $command->constraint());

        $this->documents->saveDocument($command->documentId(), $document);
    }
}
