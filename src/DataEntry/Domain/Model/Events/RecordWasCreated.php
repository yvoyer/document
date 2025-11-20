<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model\Events;

use Star\Component\Document\DataEntry\Domain\Model\DocumentId;

final class RecordWasCreated implements RecordEvent
{
    private DocumentId $recordId;
    private string $schema;

    public function __construct(DocumentId $recordId, string $schema)
    {
        $this->recordId = $recordId;
        $this->schema = $schema;
    }

    public function recordId(): DocumentId
    {
        return $this->recordId;
    }

    public function schema(): string
    {
        return $this->schema;
    }
}
