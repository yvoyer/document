<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model\Events;

use Star\Component\Document\DataEntry\Domain\Model\RecordId;

final class RecordWasCreated implements RecordEvent
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
