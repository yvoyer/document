<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model\Events;

use Star\Component\Document\DataEntry\Domain\Model\RecordId;
use Star\Component\DomainEvent\DomainEvent;

final class RecordCreated implements DomainEvent
{
    /**
     * @var RecordId
     */
    private $recordId;

    /**
     * @var string
     */
    private $schema;

    public function __construct(RecordId $recordId, string $schema)
    {
        $this->recordId = $recordId;
        $this->schema = $schema;
    }

    public function recordId(): RecordId
    {
        return $this->recordId;
    }

    public function schema(): string
    {
        return $this->schema;
    }
}
