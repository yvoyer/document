<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Infrastructure\Persistence\Doctrine\DBAL;

use Doctrine\DBAL\Connection;
use Star\Component\Document\Audit\Domain\Model\AuditDateTime;
use Star\Component\Document\Design\Domain\Messaging\Query\DataTransfer\ReadOnlyDocument;
use Star\Component\Document\Design\Domain\Messaging\Query\FindAllMyDocumentTypes;
use Star\Component\Document\Design\Domain\Model\DocumentTypeId;

final class FindAllMyDocumentTypesHandler
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function __invoke(FindAllMyDocumentTypes $query): void
    {
        $qb = $this->connection->createQueryBuilder();
        $expr = $qb->expr();
        $sql = $qb->select(
            [
                'document_type.id AS document_type_id',
                'document_type_name.content AS document_type_name',
                'document_type.structure AS structure',
                'member.id AS owner_id',
                'member.name AS owner_name',
                'document_type.created_at AS created_at',
                'document_type.updated_at AS updated_at',
            ]
        )
            ->from('document_type')
            ->innerJoin(
                'document_type',
                'member',
                'member',
                $expr->eq('document_type.created_by', 'member.id')
            )
            ->innerJoin(
                'document_type',
                'document_type_translation',
                'document_type_name',
                $expr->and(
                    $expr->eq('document_type_name.object_id', 'document_type.id'),
                    $expr->eq('document_type_name.field', $expr->literal('name')),
                    $expr->eq('document_type_name.locale', ':locale'),
                )
            )
            ->where(
                $expr->eq('document_type.created_by', ':member_id')
            )
            ->setParameters(
                [
                    'member_id' => $query->owner()->toString(),
                    'locale' => $query->locale(),
                ]
            );

        $callable = function () use ($sql) {
            $result = $sql->executeQuery();

            while ($row = $result->fetchAssociative()) {
                yield new ReadOnlyDocument(
                    DocumentTypeId::fromString($row['document_type_id']),
                    $row['document_type_name'],
                    $row['owner_id'],
                    $row['owner_name'],
                    AuditDateTime::fromString($row['created_at']),
                    AuditDateTime::fromString($row['updated_at'])
                );
            }
        };

        $query($callable);
    }
}
