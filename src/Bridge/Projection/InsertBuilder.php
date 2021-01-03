<?php declare(strict_types=1);

namespace Star\Component\Document\Bridge\Projection;

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

    public function __construct(
        string $identifier,
        ProjectionPlatform $platform,
        ProjectionMetadata $metadata
    ) {
        $this->identifier = $identifier;
        $this->platform = $platform;
        $this->metadata = $metadata;
    }

    public function execute(): void
    {
        $this->platform
            ->createInsertQuery(
                $this->metadata,
                ColumnMetadata::stringColumn($this->metadata->getIdentifier(), $this->identifier)
            );
    }
}
