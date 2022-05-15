<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Infrastructure\Persistence\Doctrine\DBAL;

use Doctrine\DBAL\Connection;
use Star\Component\Document\Audit\Infrastructure\Persistence\DBAL\AuditTrailData;
use Star\Component\Document\Design\Domain\Model\Events\DocumentCreated;
use Star\Component\Document\Design\Domain\Model\Events\PropertyAdded;
use Star\Component\Document\Design\Domain\Model\Schema\DocumentSchema;
use Star\Component\Document\Translation\Infrastructure\Persistence\DBAL\TranslatableData;
use Star\Component\DomainEvent\EventListener;

final class DocumentProjection implements EventListener
{
    use AuditTrailData;
    use TranslatableData;

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function onDocumentCreated(DocumentCreated $event): void
    {
        $this->connection->insert(
            'document',
            $this->withAuditForInsert(
                [
                    'id' => $event->documentId()->toString(),
                    'structure' => DocumentSchema::baseSchema($event->documentId())->toString(),
                ],
                $event->updatedAt(),
                $event->updatedBy()
            )
        );
        $this->connection->insert(
            'document_translation',
            $this->mergeTranslatableDataForCreation(
                'name',
                $event->name()->toSerializableString(),
                $event->name()->locale(),
                $event->documentId()->toString()
            )
        );
    }

    public function onPropertyAdded(PropertyAdded $event): void
    {
        $this->connection->update(
            'document',
            $this->withAuditForUpdate(
                [],
                $event->updatedAt(),
                $event->updatedBy()
            ),
            [
                'id' => $event->documentId()->toString(),
            ]
        );
    }

    public function listensTo(): array
    {
        return [
            DocumentCreated::class => 'onDocumentCreated',
            PropertyAdded::class => 'onPropertyAdded',
        ];
    }
}
