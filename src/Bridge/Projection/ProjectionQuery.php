<?php declare(strict_types=1);

namespace Star\Component\Document\Bridge\Projection;

final class ProjectionQuery
{
    /**
     * @var string
     */
    private $query;

    /**
     * @var mixed[]
     */
    private $parameters;

    public function __construct(string $query, array $parameters)
    {
        $this->query = $query;
        $this->parameters = $parameters;
    }

    public function toString(): string
    {
        return $this->query;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function doInsert(ProjectionPlatform $platform): void
    {
        $query = $platform->createInsertQuery($this->metadata);


    }
}
