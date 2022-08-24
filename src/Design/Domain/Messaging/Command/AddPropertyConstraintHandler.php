<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Messaging\Command;

use Star\Component\Document\Design\Domain\Model\Constraints\ConstraintRegistry;
use Star\Component\Document\Design\Domain\Model\DocumentTypeRepository;

final class AddPropertyConstraintHandler
{
    private DocumentTypeRepository $types;
    private ConstraintRegistry $registry;

    public function __construct(DocumentTypeRepository $types, ConstraintRegistry $registry)
    {
        $this->types = $types;
        $this->registry = $registry;
    }

    public function __invoke(AddPropertyConstraint $command): void
    {
        $document = $this->types->getDocumentByIdentity($command->typeId());
        $document->addPropertyConstraint(
            $command->code(),
            $command->constraintName(),
            $this->registry->createPropertyConstraint($command->constraintName(), $command->constraintData()),
            $command->addedAt()
        );

        $this->types->saveDocument($document);
    }
}
