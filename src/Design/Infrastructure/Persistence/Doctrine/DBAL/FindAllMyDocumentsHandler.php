<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Infrastructure\Persistence\Doctrine\DBAL;

use DateTimeImmutable;
use Doctrine\DBAL\Connection;
use Star\Component\Document\Design\Domain\Messaging\Query\DataTransfer\ReadOnlyDocument;
use Star\Component\Document\Design\Domain\Messaging\Query\FindAllMyDocuments;
use Star\Component\Document\Design\Domain\Model\DocumentTypeId;

final class FindAllMyDocumentsHandler
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function __invoke(FindAllMyDocuments $query): void
    {
        $qb = $this->connection->createQueryBuilder();
        $expr = $qb->expr();
        $sql = $qb->select(
            [
                'document.id AS document_id',
                'document_name.content AS document_name',
                'document.structure AS structure',
                'member.id AS owner_id',
                'member.name AS owner_name',
                'document.created_at AS created_at',
                'document.updated_at AS updated_at',
            ]
        )
            ->from('document', 'document')
            ->innerJoin(
                'document',
                'member',
                'member',
                $expr->eq('document.created_by', 'member.id')
            )
            ->innerJoin(
                'document',
                'document_translation',
                'document_name',
                $expr->and(
                    $expr->eq('document_name.object_id', 'document.id'),
                    $expr->eq('document_name.field', $expr->literal('name')),
                    $expr->eq('document_name.locale', ':locale'),
                )
            )
            ->where(
                $expr->eq('document.created_by', ':member_id')
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
                    DocumentTypeId::fromString($row['document_id']),
                    $row['document_name'],
                    $row['owner_id'],
                    $row['owner_name'],
                    new DateTimeImmutable($row['created_at']),
                    new DateTimeImmutable($row['updated_at'])
                );
            }
        };

        $query($callable);
    }
}
