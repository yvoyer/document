<?php declare(strict_types=1);

namespace Star\Component\Document\Bridge\Projection_DELETE;

interface ProjectionStrategy
{
    public function insertRow(ColumnMetadata ...$columns): void;

    public function updateRow(string $id, ColumnMetadata ...$columns): void;

    /**
     * @param string $id
     */
    public function deleteRow(string $id): void;

    public function createColumnBuilder(): InsertBuilder;

    public function assertTableExists(ProjectionMetadata $metadata): void;

    public function assertColumnExists(ColumnMetadata $metadata): void;
}
