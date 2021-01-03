<?php declare(strict_types=1);

namespace Star\Component\Document\Bridge\Projection;

use RuntimeException;

final class Projector
{
    /**
     * @var ProjectionPlatform
     */
    private $platform;

    /**
     * @var ProjectionMetadata
     */
    private $metadata;

    public function __construct(
        ProjectionPlatform $platform,
        ProjectionMetadata $metadata
    ) {
        $this->platform = $platform;
        $this->metadata = $metadata;
    }

    public function project(string $sql, array $parameters, callable $callable): array
    {
        $this->platform->assertTableExists($this->metadata);

        return $this->platform->project(
            new ProjectionQuery($sql, $parameters),
            $callable
        );
    }

    public function insert(string $id): InsertBuilder {
        $this->platform->assertTableExists($this->metadata);

        return new InsertBuilder(
            $id,
            $this->platform,
            $this->metadata
        );
    }

    public function update(string $identifier): UpdateBuilder
    {
        return new UpdateBuilder($identifier, $this->platform, $this->metadata);
    }

    public function deleteRow(string $id): void
    {
        throw new RuntimeException(__METHOD__);
        $this->platform->assertTableExists($this->metadata);

        \var_dump(__METHOD__, $id);
        throw new RuntimeException(__METHOD__);
    }
}
