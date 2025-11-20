<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Messaging\Command;

use Star\Component\Document\Design\Domain\Model\DocumentTypeRepository;

final class AddPropertyConstraintHandler
{
    private DocumentTypeRepository $types;

    public function __construct(DocumentTypeRepository $types)
    {
        $this->types = $types;
    }

    public function __invoke(AddPropertyConstraint $command): void
    {
        $document = $this->types->getDocumentByIdentity($command->typeId());
        $document->addPropertyConstraint(
            $command->propertyCode(),
            $command->constraintCode(),
            $command->constraintData()->createPropertyConstraint(),
            $command->addedAt()
        );

        $this->types->saveDocument($document);
    }
}
