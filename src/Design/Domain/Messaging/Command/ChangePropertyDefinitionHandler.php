<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Messaging\Command;

use Star\Component\Document\Design\Domain\Model\DocumentRepository;

final class ChangePropertyDefinitionHandler
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
     * @param ChangePropertyDefinition $command
     */
    public function __invoke(ChangePropertyDefinition $command)
    {
        $document = $this->documents->getDocumentByIdentity($command->documentId());
        $document->changePropertyAttribute($command->name(), $command->attribute());

        $this->documents->saveDocument($command->documentId(), $document);
    }
}
