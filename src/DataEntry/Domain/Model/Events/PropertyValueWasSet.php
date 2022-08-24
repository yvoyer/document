<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model\Events;

use Star\Component\Document\DataEntry\Domain\Model\PropertyCode;
use Star\Component\Document\DataEntry\Domain\Model\DocumentId;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\Design\Domain\Model\DocumentTypeId;

final class PropertyValueWasSet implements RecordEvent
{
    private DocumentId $recordId;
    private DocumentTypeId $documentId;
    private PropertyCode $property;
    private RecordValue $value;

    public function __construct(
        DocumentId $recordId,
        DocumentTypeId $documentId,
        PropertyCode $property,
        RecordValue $value
    ) {
        $this->recordId = $recordId;
        $this->documentId = $documentId;
        $this->property = $property;
        $this->value = $value;
    }

    public function recordId(): DocumentId
    {
        return $this->recordId;
    }

    public function documentId(): DocumentTypeId
    {
        return $this->documentId;
    }

    public function property(): PropertyCode
    {
        return $this->property;
    }

    public function value(): RecordValue
    {
        return $this->value;
    }
}
