<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model\Events;

use Star\Component\Document\DataEntry\Domain\Model\RecordId;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\Design\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\PropertyName;

final class PropertyValueWasSet implements RecordEvent
{
    /**
     * @var RecordId
     */
    private $recordId;

    /**
     * @var DocumentId
     */
    private $documentId;

    /**
     * @var PropertyName
     */
    private $property;

    /**
     * @var RecordValue
     */
    private $value;

    public function __construct(
        RecordId $recordId,
        DocumentId $documentId,
        PropertyName $property,
        RecordValue $value
    ) {
        $this->recordId = $recordId;
        $this->documentId = $documentId;
        $this->property = $property;
        $this->value = $value;
    }

    public function recordId(): RecordId
    {
        return $this->recordId;
    }

    public function documentId(): DocumentId
    {
        return $this->documentId;
    }

    public function property(): PropertyName
    {
        return $this->property;
    }

    public function value(): RecordValue
    {
        return $this->value;
    }
}
