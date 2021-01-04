<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Messaging\Query;

use Doctrine\DBAL\Connection;
use Star\Component\Document\Bridge\Projection\ColumnMetadata;
use Star\Component\Document\Bridge\Projection\Port\Doctrine\DBAL\DBALProjectionPlatform;
use Star\Component\Document\Bridge\Projection\ProjectionMetadata;
use Star\Component\Document\Bridge\Projection\Projector;
use Star\Component\Document\Design\Domain\Messaging\Query\DataTransfer\MyReadOnlyDocument;
use Star\Component\Document\Design\Domain\Model\Events\DocumentCreated;
use Star\Component\Document\Design\Domain\Model\Events\PropertyAdded;
use Star\Component\DomainEvent\EventListener;

final class FindAllMyDocumentsHandler implements EventListener
{
    private const TABLE_NAME = 'document_of_user';

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var Projector
     */
    private $projector;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
        $this->projector = new Projector(
            new DBALProjectionPlatform($connection),
            new ProjectionMetadata(self::TABLE_NAME, 'id')
        );
    }

    public function __invoke(FindAllMyDocuments $query): void
    {
        $query(
            $this->projector->project(
                'SELECT * FROM ' . self::TABLE_NAME,
                [],
                function (array $row): MyReadOnlyDocument {
                    return new MyReadOnlyDocument($row);
                }
            )
        );
    }

    public function onDocumentCreated(DocumentCreated $event): void
    {
        $this->projector
            ->insert($event->documentId()->toString())
            ->withValue(ColumnMetadata::stringColumn('type', $event->documentType()->toString()))
            ->execute();
    }

    public function onPropertyAdded(PropertyAdded $event): void
    {
        $this->projector
            ->update($event->documentId()->toString())
            ->addColumn(
                ColumnMetadata::stringColumn(
                    $event->name()->toString(),
                    $event->type()->toData()->toString()
                )
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
