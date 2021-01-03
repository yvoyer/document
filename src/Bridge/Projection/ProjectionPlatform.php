<?php declare(strict_types=1);

namespace Star\Component\Document\Bridge\Projection;

interface ProjectionPlatform
{
    /**
     * @param ProjectionQuery $query
     * @param callable $callable
     * @return mixed[]
     */
    public function project(ProjectionQuery $query, callable $callable): array;

    public function createInsertQuery(ProjectionMetadata $metadata, ColumnMetadata ...$columns): void;

    public function createUpdateQuery(ProjectionMetadata $metadata, ColumnMetadata $column, string $identifier): void;

    public function assertColumnExists(ProjectionMetadata $projection, ColumnMetadata $column): void;

    public function assertTableExists(ProjectionMetadata $metadata): void;
}
