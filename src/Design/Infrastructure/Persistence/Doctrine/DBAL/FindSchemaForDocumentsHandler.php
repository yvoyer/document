<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Infrastructure\Persistence\Doctrine\DBAL;

use Doctrine\DBAL\Connection;
use Star\Component\Document\Design\Domain\Messaging\Query\DataTransfer\ReadOnlyDocumentSchema;
use Star\Component\Document\Design\Domain\Messaging\Query\FindSchemaForDocuments;
use Star\Component\Document\Design\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\Schema\DocumentSchema;
use Star\Component\Document\Design\Domain\Model\Templating\NamedDocument;
use Star\Component\Document\Design\Infrastructure\Persistence\Doctrine\DesignTableStore;

final class FindSchemaForDocumentsHandler
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function __invoke(FindSchemaForDocuments $query): void
    {
        $qb = $this->connection->createQueryBuilder();
        $expr = $qb->expr();
        $stmt = $qb
            ->select('*')
            ->from(DesignTableStore::DOCUMENT, 'document')
            ->andWhere($expr->in('document.id', ':document_ids'))
            ->setParameter('document_ids', $query->documentIntIds(), Connection::PARAM_STR_ARRAY);

        $query(
            function () use ($stmt) {
                $result = $stmt->executeQuery();
                foreach ($result->iterateAssociativeIndexed() as $documentId => $row) {
                    yield $documentId => new ReadOnlyDocumentSchema(
                        DocumentId::fromString($documentId),
                        new NamedDocument($row['name']),
                        DocumentSchema::fromJsonString($row['schema'])
                    );
                }
            }
        );
    }
}
