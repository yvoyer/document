<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model\Events;

use Star\Component\Document\DataEntry\Domain\Model\PropertyCode;
use Star\Component\Document\DataEntry\Domain\Model\DocumentId;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\Design\Domain\Model\DocumentTypeId;

final class PropertyValueWasChanged implements RecordEvent
{
    private DocumentId $recordId;
    private DocumentTypeId $documentId;
    private PropertyCode $property;
    private RecordValue $fromValue;
    private RecordValue $toValue;

    public function __construct(
        DocumentId $recordId,
        DocumentTypeId $documentId,
        PropertyCode $property,
        RecordValue $fromValue,
        RecordValue $toValue
    ) {
        $this->recordId = $recordId;
        $this->documentId = $documentId;
        $this->property = $property;
        $this->fromValue = $fromValue;
        $this->toValue = $toValue;
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

    public function fromValue(): RecordValue
    {
        return $this->fromValue;
    }

    public function toValue(): RecordValue
    {
        return $this->toValue;
    }
}
