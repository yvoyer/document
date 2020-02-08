<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model\Events;

use Star\Component\Document\DataEntry\Domain\Model\RecordId;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\Design\Domain\Model\PropertyName;

final class RecordValueChanged
{
    /**
     * @var RecordId
     */
    private $recordId;

    /**
     * @var PropertyName
     */
    private $property;

    /**
     * @var RecordValue
     */
    private $fromValue;

    /**
     * @var RecordValue
     */
    private $toValue;

    public function __construct(
        RecordId $recordId,
        PropertyName $property,
        RecordValue $fromValue,
        RecordValue $toValue
    ) {
        $this->recordId = $recordId;
        $this->property = $property;
        $this->fromValue = $fromValue;
        $this->toValue = $toValue;
    }

    public function recordId(): RecordId
    {
        return $this->recordId;
    }

    public function property(): PropertyName
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
