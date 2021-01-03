<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Infrastructure\Persistence\Doctrine;

use Star\Component\Document\Design\Domain\Model\DocumentAggregate;
use Star\Component\Document\Design\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\DocumentNotFound;
use Star\Component\Document\Design\Domain\Model\DocumentRepository;
use Star\Component\DomainEvent\AggregateRoot;
use Star\Component\DomainEvent\Ports\Doctrine\DBALEventStore;

final class DocumentEventStore extends DBALEventStore implements DocumentRepository
{
    public function getDocumentByIdentity(DocumentId $id): DocumentAggregate
    {
        return $this->getAggregateWithId($id->toString());
    }

    public function saveDocument(DocumentAggregate $document): void
    {
        $this->persistAggregate($document->getIdentity()->toString(), $document);
    }

    protected function tableName(): string
    {
        return 'documents';
    }

    protected function createAggregateFromStream(array $events): AggregateRoot
    {
        return DocumentAggregate::fromStream($events);
    }

    protected function handleNoEventFound(string $id): void
    {
        throw new DocumentNotFound($id);
    }
}
