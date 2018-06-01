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

    /**
     * @param AddPropertyConstraint $command
     */
    public function __invoke(AddPropertyConstraint $command)
    {
        $document = $this->documents->getDocumentByIdentity($command->documentId());
        $document->addConstraint($command->name(), $command->constraintName(), $command->constraint());

        $this->documents->saveDocument($command->documentId(), $document);
    }
}
