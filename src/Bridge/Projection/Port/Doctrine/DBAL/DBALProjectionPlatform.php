<?php declare(strict_types=1);

namespace Star\Component\Document\Bridge\Projection\Port\Doctrine\DBAL;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Types\Types;
use Star\Component\Document\Bridge\Projection\ColumnMetadata;
use Star\Component\Document\Bridge\Projection\ProjectionMetadata;
use Star\Component\Document\Bridge\Projection\ProjectionPlatform;
use Star\Component\Document\Bridge\Projection\ProjectionQuery;
use function constant;
use function defined;
use function strtoupper;

final class DBALProjectionPlatform implements ProjectionPlatform
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function project(ProjectionQuery $query, callable $callable): array
    {
        $result = [];
        $stmt = $this->connection->executeQuery($query->toString(), $query->getParameters());
        while ($row = $stmt->fetchAssociative()) {
            $result[] = $callable($row);
        }

        $stmt->free();

        return $result;
    }

    public function createInsertQuery(ProjectionMetadata $metadata, ColumnMetadata ...$columns): void
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->insert($metadata->getTableName());

        $values = [];
        foreach ($columns as $i => $column) {
            $this->assertColumnExists($metadata, $column);
            $values[$column->getName()] = '?';
            $qb->setParameter($i, $column->getValue());
        }

        $qb
            ->values($values)
            ->execute();
    }

    public function createUpdateQuery(ProjectionMetadata $metadata, ColumnMetadata $column, string $identifier): void
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->update($metadata->getTableName())
            ->set($column->getName(), ':value')
            ->where($qb->expr()->eq($metadata->getIdentifier(), ':identifier'))
            ->setParameter('value', $column->getValue(), $this->guessParameterType($column))
            ->setParameter('identifier', $identifier, ParameterType::STRING)
            ->execute();
    }

    public function assertTableExists(ProjectionMetadata $metadata): void
    {
        $manager = $this->connection->getSchemaManager();
        $tableName = $metadata->getTableName();
        if (! $manager->tablesExist([$tableName])) {
            $originalSchema = $manager->createSchema();
            $newSchema = $manager->createSchema();
            $table = $newSchema->createTable($tableName);
            $table->addColumn($metadata->getIdentifier(), Types::STRING);

            $sqlStrings = $originalSchema->getMigrateToSql($newSchema, $this->connection->getDatabasePlatform());
            foreach ($sqlStrings as $sql) {
                $this->connection->exec($sql);
            }
        }
    }

    public function assertColumnExists(ProjectionMetadata $projection, ColumnMetadata $column): void
    {
        $manager = $this->connection->getSchemaManager();
        $tableName = $projection->getTableName();
        $originalSchema = $manager->createSchema();
        $newSchema = $manager->createSchema();

        $newTable = $newSchema->getTable($tableName);
        $columnName = $column->getName();
        if (! $newTable->hasColumn($columnName)) {
            $newTable->addColumn($columnName, $column->getType(), ['notNull' => false]);

            $sqlStrings = $originalSchema->getMigrateToSql($newSchema, $this->connection->getDatabasePlatform());
            foreach ($sqlStrings as $sql) {
                $this->connection->exec($sql);
            }
        }
    }

    private function guessParameterType(ColumnMetadata $metadata): int
    {
        $constant = ParameterType::class . '::' . strtoupper($metadata->getType());
        if (! defined($constant)) {
            return ParameterType::STRING; // default
        }

        return constant($constant);
    }
}
