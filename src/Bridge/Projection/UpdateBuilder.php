<?php declare(strict_types=1);

namespace Star\Component\Document\Bridge\Projection;

final class UpdateBuilder
{
    /**
     * @var string
     */
    private $identifier;

    /**
     * @var ProjectionPlatform
     */
    private $platform;

    /**
     * @var ProjectionMetadata
     */
    private $projection;

    public function __construct(
        string $identifier,
        ProjectionPlatform $platform,
        ProjectionMetadata $projection
    ) {
        $this->identifier = $identifier;
        $this->platform = $platform;
        $this->projection = $projection;
    }

    public function addColumn(ColumnMetadata $column): UpdateBuilder
    {
        $this->platform->assertColumnExists($this->projection, $column);

        $this->platform->createUpdateQuery($this->projection, $column, $this->identifier);

        return $this;
    }
}
