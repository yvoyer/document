<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Messaging\Command;

use Star\Component\Document\Design\Domain\Exception\NotFoundTransformer;
use Star\Component\Document\Design\Domain\Model\DocumentRepository;
use Star\Component\Document\Design\Domain\Model\Transformation\TransformerFactory;

final class AddValueTransformerOnPropertyHandler
{
    /**
     * @var TransformerFactory
     */
    private $factory;

    /**
     * @var DocumentRepository
     */
    private $documents;

    public function __construct(TransformerFactory $factory, DocumentRepository $documents)
    {
        $this->factory = $factory;
        $this->documents = $documents;
    }

    public function __invoke(AddValueTransformerOnProperty $command): void
    {
        if (!$this->factory->transformerExists($command->transformerId())) {
            throw new NotFoundTransformer($command->transformerId());
        }

        $document = $this->documents->getDocumentByIdentity($command->documentId());
        $document->addPropertyTransformer($command->propertyName(), $command->transformerId());
        $this->documents->saveDocument($command->documentId(), $document);
    }
}
