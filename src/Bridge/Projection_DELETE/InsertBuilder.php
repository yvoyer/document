<?php declare(strict_types=1);

namespace Star\Component\Document\Bridge\Projection_DELETE;

final class InsertBuilder
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
    private $metadata;

    /**
     * @var ColumnMetadata[]
     */
    private $otherFields = [];

    public function __construct(
        string $identifier,
        ProjectionPlatform $platform,
        ProjectionMetadata $metadata
    ) {
        $this->identifier = $identifier;
        $this->platform = $platform;
        $this->metadata = $metadata;
    }

    public function withValue(ColumnMetadata $metadata): self
    {
        $this->otherFields[] = $metadata;

        return $this;
    }

    public function execute(): void
    {
        $this->platform
            ->createInsertQuery(
                $this->metadata,
                ColumnMetadata::stringColumn($this->metadata->getIdentifier(), $this->identifier),
                ...$this->otherFields
            );
    }
}
