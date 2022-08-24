<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Infrastructure\Persistence\Doctrine\DBAL;

use Doctrine\DBAL\Connection;
use Star\Component\Document\Design\Domain\Messaging\Query\DataTransfer\SchemaOfDocument;
use Star\Component\Document\Design\Domain\Messaging\Query\FindSchemaForDocuments;
use Star\Component\Document\Design\Domain\Model\DocumentTypeId;
use Star\Component\Document\Design\Domain\Model\DocumentName;
use Star\Component\Document\Design\Domain\Model\Schema\DocumentSchema;

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
        $locale = $query->locale();
        $stmt = $qb
            ->select(
                [
                    'document.id AS id',
                    'name.content AS name',
                    'document.structure AS structure',
                ]
            )
            ->from('document', 'document')
            ->innerJoin(
                'document',
                'document_translation',
                'name',
                $expr->and(
                    $expr->eq('name.field', $expr->literal('name')),
                    $expr->eq('name.locale', ':locale'),
                    $expr->eq('name.object_id', 'document.id')
                )
            )
            ->andWhere($expr->in('document.id', ':document_ids'))
            ->setParameters(
                [
                    'document_ids' => $query->documentIntIds(),
                    'locale' => $locale,
                ],
                [
                    'document_ids' => Connection::PARAM_STR_ARRAY,
                ]
            );

        $query(
            function () use ($stmt, $locale) {
                $result = $stmt->executeQuery();
                foreach ($result->iterateAssociativeIndexed() as $documentId => $row) {
                    yield $documentId => new SchemaOfDocument(
                        DocumentTypeId::fromString($documentId),
                        new DocumentName($row['name'], $locale),
                        DocumentSchema::fromJsonString($row['structure'])
                    );
                }
            }
        );
    }
}
