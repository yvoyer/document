<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Messaging\Command;

use Star\Component\Document\Design\Domain\Model\Constraints\ConstraintRegistry;
use Star\Component\Document\Design\Domain\Model\DocumentRepository;

final class AddPropertyConstraintHandler
{
    /**
     * @var DocumentRepository
     */
    private $documents;

    /**
     * @var ConstraintRegistry
     */
    private $registry;

    public function __construct(DocumentRepository $documents, ConstraintRegistry $registry)
    {
        $this->documents = $documents;
        $this->registry = $registry;
    }

    public function __invoke(AddPropertyConstraint $command): void
    {
        $document = $this->documents->getDocumentByIdentity($command->documentId());
        $document->addPropertyConstraint(
            $command->name(),
            $command->constraintName(),
            $this->registry->createPropertyConstraint($command->constraintName(), $command->constraintData())
        );

        $this->documents->saveDocument($document);
    }
}
