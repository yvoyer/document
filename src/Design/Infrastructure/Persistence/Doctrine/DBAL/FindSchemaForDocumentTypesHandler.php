<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Infrastructure\Persistence\Doctrine\DBAL;

use Doctrine\DBAL\Connection;
use Star\Component\Document\Design\Domain\Messaging\Query\DataTransfer\SchemaOfDocument;
use Star\Component\Document\Design\Domain\Messaging\Query\FindSchemaForDocumentTypes;
use Star\Component\Document\Design\Domain\Model\DocumentTypeId;
use Star\Component\Document\Design\Domain\Model\DocumentTypeName;
use Star\Component\Document\Design\Domain\Model\Schema\DocumentSchema;

final class FindSchemaForDocumentTypesHandler
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function __invoke(FindSchemaForDocumentTypes $query): void
    {
        $qb = $this->connection->createQueryBuilder();
        $expr = $qb->expr();
        $locale = $query->locale();
        $stmt = $qb
            ->select(
                [
                    'document_type.id AS id',
                    'name.content AS name',
                    'document_type.structure AS structure',
                ]
            )
            ->from('document_type')
            ->innerJoin(
                'document_type',
                'document_type_translation',
                'name',
                $expr->and(
                    $expr->eq('name.field', $expr->literal('name')),
                    $expr->eq('name.locale', ':locale'),
                    $expr->eq('name.object_id', 'document_type.id')
                )
            )
            ->andWhere($expr->in('document_type.id', ':document_type_ids'))
            ->setParameters(
                [
                    'document_type_ids' => $query->documentIntIds(),
                    'locale' => $locale,
                ],
                [
                    'document_type_ids' => Connection::PARAM_STR_ARRAY,
                ]
            );

        $query(
            function () use ($stmt, $locale) {
                $result = $stmt->executeQuery();
                foreach ($result->iterateAssociativeIndexed() as $typeId => $row) {
                    yield $typeId => new SchemaOfDocument(
                        DocumentTypeId::fromString($typeId),
                        DocumentTypeName::fromLocalizedString($row['name'], $locale),
                        DocumentSchema::fromJsonString($row['structure'])
                    );
                }
            }
        );
    }
}
