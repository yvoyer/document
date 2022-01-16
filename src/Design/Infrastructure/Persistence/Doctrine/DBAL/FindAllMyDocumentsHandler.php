<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Infrastructure\Persistence\Doctrine\DBAL;

use DateTimeImmutable;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types;
use Star\Component\Document\Design\Domain\Messaging\Query\DataTransfer\ReadOnlyDocument;
use Star\Component\Document\Design\Domain\Messaging\Query\FindAllMyDocuments;
use Star\Component\Document\Design\Domain\Model\DocumentId;
use Star\Component\Document\Design\Infrastructure\Persistence\Doctrine\DesignTableStore;
use Star\Component\Document\Membership\Infrastructure\Persistence\Doctrine\MembershipTableStore;

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
                'document.name AS document_name',
                'member.id AS owner_id',
                'member.name AS owner_name',
                'document.created_at AS created_at',
                'document.updated_at AS updated_at',
            ]
        )
            ->from(DesignTableStore::DOCUMENT, 'document')
            ->innerJoin(
                'document',
                MembershipTableStore::MEMBER,
                'member',
                $expr->eq('document.created_by', 'member.id')
            )
            ->where($expr->eq('document.created_by', ':member_id'))
            ->setParameter(
                'member_id',
                $query->owner()->toString(),
                Types::STRING
            )
            ->executeQuery();

        $callable = function () use ($sql) {
            while ($row = $sql->fetch()) {
                yield new ReadOnlyDocument(
                    DocumentId::fromString($row['document_id']),
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
