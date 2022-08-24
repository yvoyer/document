<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Infrastructure\Persistence\Doctrine\DBAL;

use Star\Component\Document\Design\Domain\Model\DocumentTypeAggregate;
use Star\Component\Document\Design\Domain\Model\DocumentTypeId;
use Star\Component\Document\Design\Domain\Model\DocumentTypeNotFound;
use Star\Component\Document\Design\Domain\Model\DocumentTypeRepository;
use Star\Component\DomainEvent\AggregateRoot;
use Star\Component\DomainEvent\Ports\Doctrine\DBALEventStore;

final class DocumentTypeEventStore extends DBALEventStore implements DocumentTypeRepository
{
    public function getDocumentByIdentity(DocumentTypeId $id): DocumentTypeAggregate
    {
        return $this->getAggregateWithId($id->toString());
    }

    public function saveDocument(DocumentTypeAggregate $document): void
    {
        $this->persistAggregate($document->getIdentity()->toString(), $document);
    }

    protected function tableName(): string
    {
        return '_events_documents';
    }

    protected function createAggregateFromStream(array $events): AggregateRoot
    {
        return DocumentTypeAggregate::fromStream($events);
    }

    protected function handleNoEventFound(string $id): void
    {
        throw new DocumentTypeNotFound($id);
    }
}
