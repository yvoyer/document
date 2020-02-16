<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Messaging\Command;

use Star\Component\Document\Design\Domain\Model\DocumentRepository;

final class AddPropertyParameterHandler
{
    /**
     * @var DocumentRepository
     */
    private $documents;

    public function __construct(DocumentRepository $documents)
    {
        $this->documents = $documents;
    }

    public function __invoke(AddPropertyParameter $command): void
    {
        $document = $this->documents->getDocumentByIdentity($command->documentId());
        $document->addPropertyParameter(
            $command->property(),
            $command->parameterName(),
            $command->parameterData()->createParameter()
        );

        $this->documents->saveDocument($document);
    }
}
