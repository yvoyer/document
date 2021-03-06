<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Messaging\Command;

use Star\Component\Document\DataEntry\Domain\Model\RecordId;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\Design\Domain\Model\DocumentId;
use Star\Component\DomainEvent\Messaging\Command;

final class SetRecordValue implements Command
{
    /**
     * @var DocumentId
     */
    private $documentId;

    /**
     * @var RecordId
     */
    private $recordId;

    /**
     * @var string
     */
    private $property;

    /**
     * @var RecordValue
     */
    private $value;

    public function __construct(
        DocumentId $documentId,
        RecordId $recordId,
        string $property,
        RecordValue $value
    ) {
        $this->documentId = $documentId;
        $this->recordId = $recordId;
        $this->property = $property;
        $this->value = $value;
    }

    public function documentId(): DocumentId
    {
        return $this->documentId;
    }

    public function recordId(): RecordId
    {
        return $this->recordId;
    }

    public function property(): string
    {
        return $this->property;
    }

    public function value(): RecordValue
    {
        return $this->value;
    }
}
