<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Infrastructure\Persistence\Doctrine\DBAL;

use Behat\Transliterator\Transliterator;
use Doctrine\DBAL\Connection;
use Star\Component\Document\Audit\Infrastructure\Persistence\DBAL\AuditTrailData;
use Star\Component\Document\Design\Domain\Model\DocumentTypeId;
use Star\Component\Document\Design\Domain\Model\Events\PropertyWasAdded;
use Star\Component\Document\Translation\Infrastructure\Persistence\DBAL\TranslatableData;
use Star\Component\DomainEvent\EventListener;
use function json_encode;

final class PropertyProjection implements EventListener
{
    use AuditTrailData;
    use TranslatableData;

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function onPropertyAdded(PropertyWasAdded $event): void
    {
        $this->connection->insert(
            'document_property',
            [
                'type' => $event->type()->toData()->toString(),
                'code' => $code = Transliterator::urlize($event->name()->toSerializableString(), '_'),
                'document_id' => $event->documentId()->toString(),
                'constraints' => json_encode([]),
                'parameters' => json_encode([]),
            ]
        );
        $this->connection->insert(
            'document_property_translation',
            $this->mergeTranslatableDataForCreation(
                'name',
                $event->name()->toString(),
                $event->name()->locale(),
                $this->getPropertyId($event->documentId(), $code)
            )
        );
    }

    private function getPropertyId(DocumentTypeId $documentId, string $code): string
    {
        $qb = $this->connection->createQueryBuilder();
        $expr = $qb->expr();

        return $qb->select('property.id')
            ->from('document_property', 'property')
            ->andWhere(
                $expr->eq('property.code', ':code'),
                $expr->eq('property.document_id', ':document_id')
            )
            ->setParameters(
                [
                    'code' => $code,
                    'document_id' => $documentId->toString(),
                ]
            )
            ->fetchOne();
    }

    public function listensTo(): array
    {
        return [
            PropertyWasAdded::class => 'onPropertyAdded',
        ];
    }
}
