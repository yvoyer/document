<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Infrastructure\Persistence\Doctrine\DBAL;

use App\SchemaManager;
use Doctrine\DBAL\Connection;
use Star\Component\Document\Bridge\Projection_DELETE\Projector;
use Star\Component\Document\Design\Domain\Model\Events\DocumentCreated;
use Star\Component\Document\Design\Infrastructure\Persistence\Doctrine\DesignTableStore;
use Star\Component\DomainEvent\EventListener;

final class DocumentSchemaManager implements EventListener
{
    private Projector $projector;
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function onDocumentCreated(DocumentCreated $event): void
    {
        $this->connection->insert(
            DesignTableStore::DOCUMENT,
            [
                SchemaManager::PK_PUBLIC => $event->documentId()->toString(),
                'name' => $event->name()->toSerializableString(),
                'created_by' => $event->createdBy()->toString(),
                'created_at' => $event->createdAt()->format('Y-m-d H:i:s'),
                'updated_by' => $event->createdBy()->toString(),
                'updated_at' => $event->createdAt()->format('Y-m-d H:i:s'),
            ]
        );
    }

#    public function onPropertyAdded(PropertyAdded $event): void
    #   {
    #      $this->markAsUpdated($event->documentId(), $event->addedAt(), $event->addedBy());
    # }

    #private function markAsUpdated(DateTimeInterface $updatedAt, string $updatedBy): void
    #{#

    #}

    public function listensTo(): array
    {
        return [
            DocumentCreated::class => 'onDocumentCreated',
            #       PropertyAdded::class => 'onPropertyAdded',
        ];
    }
}
